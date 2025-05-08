<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Salle;
use App\Models\Makeup;
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
use App\Models\MakeupPortfolioItem;
use App\Models\NegafaPortfolioItem;
use App\Models\TraiteurAvailability;
use Illuminate\Support\Facades\Auth;
use App\Models\PhotographerPortfolioItem;

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


    $unavailable = TraiteurAvailability::where('traiteur_id', $traiteurId)
        ->where('date', $date)
        ->where('is_available', false)
        ->exists();

    
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

    // Déterminer la catégorie active (par défaut ou sélectionnée)
    $activeCategoryName = $request->get('category', 'menu');
    $activeCategory = $categories->where('name', $activeCategoryName)->first() ?? $categories->first();

    // Variable pour stocker les données spécifiques à la catégorie
    $categoryData = [];


    $services = Service::where('traiteur_id', $traiteurId)
    ->where('category_id', $activeCategory->id)
    ->get();


    switch($activeCategory->name) {
        case 'menu':
            // Pour les menus, on charge les items associés
            $services->load('menuItems');
            break;

        case 'vetements':
            // Pour les vêtements, on récupère les items directement
            $categoryData['clothingItems'] = Clothing::whereIn('service_id', $services->pluck('id'))->get();
            break;

        case 'negafa':
            // Récupérer toutes les négafas associées aux services
            $negafas = Negafa::whereIn('service_id', $services->pluck('id'))->get();

            // Pour chaque négafa, charger son portfolio
            foreach ($negafas as $negafa) {
                $negafa->portfolioItems = NegafaPortfolioItem::where('negafa_id', $negafa->id)->get();
            }

            $categoryData['negafas'] = $negafas;
            break;

        case 'maquillage':
            // Similaire pour maquillage
            $makeups = Makeup::whereIn('service_id', $services->pluck('id'))->get();

            foreach ($makeups as $makeup) {
                $makeup->portfolioItems = MakeupPortfolioItem::where('makeup_id', $makeup->id)->get();
            }

            $categoryData['makeups'] = $makeups;
            break;

        case 'photographer':
            // Similaire pour photographes
            $photographers = Photographer::whereIn('service_id', $services->pluck('id'))->get();

            foreach ($photographers as $photographer) {
                $photographer->portfolioItems = PhotographerPortfolioItem::where('photographer_id', $photographer->id)->get();
            }

            $categoryData['photographers'] = $photographers;
            break;

        case 'salles':
            // Pour les salles
            $categoryData['salles'] = Salle::whereIn('service_id', $services->pluck('id'))->get();
            break;

        case 'decoration':
            // Pour les décorations
            $categoryData['decorations'] = Decoration::whereIn('service_id', $services->pluck('id'))->get();
            break;

        case 'amariya':
            // Pour les amariyas
            $categoryData['amariyas'] = Amariya::whereIn('service_id', $services->pluck('id'))->get();
            break;

        case 'animation':
            // Pour les animations
            $categoryData['animations'] = Animation::whereIn('service_id', $services->pluck('id'))->get();
            break;
    }

    return view('planning.services', compact('traiteur', 'date', 'categories', 'activeCategory', 'services' ,'categoryData'));
}

public function getAllDatesStatus($traiteurId)
{
    // Récupérer les dates indisponibles (explicitement marquées comme non disponibles)
    $unavailableDatesFromAvailability = TraiteurAvailability::where('traiteur_id', $traiteurId)
        ->where('is_available', false)
        ->pluck('date')
        ->map(function($date) {
            return Carbon::parse($date)->format('Y-m-d');
        });

    // Récupérer les dates déjà réservées
    $reservedDates = Reservation::where('traiteur_id', $traiteurId)
        ->where('status', '!=', 'cancelled')
        ->pluck('event_date')
        ->map(function($date) {
            return Carbon::parse($date)->format('Y-m-d');
        });

    // Combiner les deux collections
    $unavailableDates = $unavailableDatesFromAvailability->merge($reservedDates)->unique();

    return response()->json([
        'unavailableDates' => $unavailableDates
    ]);
}



}
