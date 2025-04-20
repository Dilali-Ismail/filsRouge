<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Salle;
use App\Models\Negafa;
use App\Models\Amariya;
use App\Models\Service;
use App\Models\Clothing;
use App\Models\MenuItem;
use App\Models\Traiteur;
use App\Models\Animation;
use App\Models\Decoration;
use App\Models\Reservation;
use App\Models\Photographer;
use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use App\Models\ReservationService;
use App\Models\TraiteurAvailability;
use Illuminate\Support\Facades\Auth;

class PlanningController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(){

        if (!Auth::user()->isMariee()) {
            return redirect()->route('welcome')->with('error', 'Accès réservé aux mariées.');
        }


        $traiteurs = Traiteur::where('is_verified', true)->with('user')->get();


        $recommendedTraiteurs = collect();


        $mariee = Auth::user()->mariee;


        if ($mariee && $mariee->city) {
            $recommendedTraiteurs = Traiteur::where('city', $mariee->city)
                                    ->where('is_verified', true)
                                    ->with('user')
                                    ->get();
        }


        return view('planning.index', compact('traiteurs', 'recommendedTraiteurs'));

    }

    public function getTraiteurDetails($id)
    {
        $traiteur = Traiteur::with('user')->findOrFail($id);

        return response()->json([
            'traiteur' => $traiteur
        ]);
    }


public function getAvailableDates($traiteurId)
{

    $availabilities = TraiteurAvailability::where('traiteur_id', $traiteurId)
        ->where('is_available', true)
        ->get()
        ->pluck('date')
        ->map(function($date) {
            return Carbon::parse($date)->format('Y-m-d');
        });

    return response()->json([
        'availableDates' => $availabilities
    ]);
}

public function checkDateAvailability(Request $request, $traiteurId)
{
    $request->validate([
        'date' => 'required|date_format:Y-m-d',
    ]);

    $date = $request->date;

    // Vérifie si la date est explicitement marquée comme non disponible
    $unavailable = TraiteurAvailability::where('traiteur_id', $traiteurId)
        ->where('date', $date)
        ->where('is_available', false)
        ->exists();

    // Vérifie si la date est déjà réservée
    $reserved = Reservation::where('traiteur_id', $traiteurId)
        ->where('event_date', $date)
        ->where('status', '!=', 'cancelled')
        ->exists();

    $isAvailable = !$unavailable && !$reserved;

    return response()->json([
        'available' => $isAvailable
    ]);
}



public function services(Request $request, $traiteurId)
{
    if (!Auth::user()->isMariee()) {
        return redirect()->route('welcome')->with('error', 'Accès réservé aux mariées.');
    }

    $traiteur = Traiteur::with('user')->findOrFail($traiteurId);

    if (!$request->has('date')) {
        return redirect()->route('planning.index')->with('error', 'Veuillez sélectionner une date.');
    }

    $date = $request->date;

    $categories = ServiceCategory::all();

    $activeCategory = $categories->where('name', 'menu')->first() ?? $categories->first();

    $services = Service::where('traiteur_id', $traiteurId)
    ->where('category_id', $activeCategory->id)
    ->with(['menuItems'])
    ->get();

    return view('planning.services', compact('traiteur', 'date', 'categories', 'activeCategory', 'services'));
}

public function storeReservation(Request $request, $traiteurId)
{

    if (!Auth::user()->isMariee()) {
        return redirect()->route('welcome')->with('error', 'Accès réservé aux mariées.');
    }


    $request->validate([
        'event_date' => 'required|date_format:Y-m-d',
        'serivces_json' => 'required|json',
    ]);


    $traiteur = Traiteur::findOrFail($traiteurId);


    $services = json_decode($request->services_json, true);


    $totalAmount = 0;
    foreach ($services as $service) {
        $totalAmount += floatval($service['price']);
    }


    $reservation = Reservation::create([
        'mariee_id' => Auth::user()->mariee->id,
        'traiteur_id' => $traiteur->id,
        'event_date' => $request->event_date,
        'total_amount' => $totalAmount,
        'status' => 'pending'
    ]);


    foreach ($services as $service) {
        // Déterminer le type de service et son modèle
        $serviceType = $service['type'];
        $serviceId = $service['id'];
        $serviceItemType = null;
        $serviceItemId = null;
        $relatedServiceId = null;

        // Traiter selon le type de service
        switch ($serviceType) {
            case 'menu':
                // Pour un menu complet
                $serviceObj = Service::find($serviceId);
                if ($serviceObj) {
                    $relatedServiceId = $serviceObj->id;
                }
                break;

            case 'menu-item':
                // Pour un item de menu spécifique
                $menuItem = MenuItem::find($serviceId);
                if ($menuItem) {
                    $relatedServiceId = $menuItem->service_id;
                    $serviceItemId = $serviceId;
                    $serviceItemType = 'App\\Models\\MenuItem';
                }
                break;

            case 'vetement':
                // Pour un vêtement
                $clothing = Clothing::find($serviceId);
                if ($clothing) {
                    $relatedServiceId = $clothing->service_id;
                    $serviceItemId = $serviceId;
                    $serviceItemType = 'App\\Models\\ClothingItem';
                }
                break;

            case 'negafa':
                $negafa = Negafa::find($serviceId);
                if ($negafa) {
                    $relatedServiceId = $negafa->service_id;
                    $serviceItemId = $serviceId;
                    $serviceItemType = 'App\\Models\\Negafa';
                }
                break;

            case 'maquillage':
                $makeup = Makeup::find($serviceId);
                if ($makeup) {
                    $relatedServiceId = $makeup->service_id;
                    $serviceItemId = $serviceId;
                    $serviceItemType = 'App\\Models\\Makeup';
                }
                break;

            case 'photographer':
                $photographer = Photographer::find($serviceId);
                if ($photographer) {
                    $relatedServiceId = $photographer->service_id;
                    $serviceItemId = $serviceId;
                    $serviceItemType = 'App\\Models\\Photographer';
                }
                break;

            case 'salle':
                $salle = Salle::find($serviceId);
                if ($salle) {
                    $relatedServiceId = $salle->service_id;
                    $serviceItemId = $serviceId;
                    $serviceItemType = 'App\\Models\\Salle';
                }
                break;

            case 'decoration':
                $decoration = Decoration::find($serviceId);
                if ($decoration) {
                    $relatedServiceId = $decoration->service_id;
                    $serviceItemId = $serviceId;
                    $serviceItemType = 'App\\Models\\Decoration';
                }
                break;

            case 'amariya':
                $amariya = Amariya::find($serviceId);
                if ($amariya) {
                    $relatedServiceId = $amariya->service_id;
                    $serviceItemId = $serviceId;
                    $serviceItemType = 'App\\Models\\Amariya';
                }
                break;

            case 'animation':
                $animation = Animation::find($serviceId);
                if ($animation) {
                    $relatedServiceId = $animation->service_id;
                    $serviceItemId = $serviceId;
                    $serviceItemType = 'App\\Models\\Animation';
                }
                break;
        }

        
        if ($relatedServiceId) {
            ReservationService::create([
                'reservation_id' => $reservation->id,
                'service_id' => $relatedServiceId,
                'service_item_id' => $serviceItemId,
                'service_item_type' => $serviceItemType,
                'price' => $service['price'],
                'quantity' => 1
            ]);
        }
    }


    return redirect()->route('planning.traiteur.payment', ['reservation' => $reservation->id]);
}

}
