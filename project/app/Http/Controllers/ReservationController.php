<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Traiteur;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\ReservationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);

    }

    public function index(){
        if (Auth::user()->isMariee()) {
            // Pour les mariées: leurs propres réservations
            $reservations = Reservation::where('mariee_id', Auth::user()->mariee->id)
                                       ->with(['traiteur'])
                                       ->orderBy('event_date', 'desc')
                                       ->paginate(10);

            return view('reservations.index', compact('reservations'));

        }else if(Auth::user()->isTraiteur()){


           $reservations  = Reservation::where('traiteur_id',Auth::user()->traiteur->id)
                                    ->with(['mariee'])
                                    ->orderBy('event_date','desc')
                                    ->paginate(10);
            return view('reservations.traiteur-index',compact($reservations));

        }else {
            return redirect()->route('welcome')
                ->with('error', 'Accès non autorisé.');
        }
    }

    public function  create(Request $request){
        if (!Auth::user()->isMariee()){
            return redirect()->route('welcome')
                ->with('error', 'Accès réservé aux futurs mariés.');
        }

        $request->validate([
            'traiteur_id' => 'required|exists:traiteurs,id',
            'event_date' => 'required|date|after:today',
        ]);


        $traiteur = Traiteur::with([
            'services.category',
            'services.menuItems',
            'services.decorations',
            'services.amariyas',
            'services.animations',
            'services.negafas',
            'services.makeups',
            'services.clothing',
            'services.salles',
        ])->findOrFail($request->traiteur_id);

        $eventDate = $request->event_date;
        //if traiteur dispo a dans cette date
        if (!$traiteur->isAvailableOn($eventDate)) {
            return redirect()->route('planning.index')
                ->with('error', 'Ce traiteur n\'est pas disponible à cette date.');
        }

        return view('reservations.create', compact('traiteur', 'eventDate'));
    }

    public function store(Request $request){

        if (!Auth::user()->isMariee()) {
            return redirect()->route('welcome')
                ->with('error', 'Accès réservé aux futur(e)s marié(e)s.');
        }

        $request->validate([
            'traiteur_id' => 'required|exists:traiteurs,id',
            'event_date' => 'required|date|after:today',
            'services' => 'required|array',
            'services.*' => 'exists:services,id',
            'items' => 'required|array',
            'items.*' => 'required',
        ]);

        $totalAmount = 0 ;

        foreach ($request->items as $serviceId => $itemData) {
            foreach ($itemData as $itemId => $quantity) {
                if ($quantity > 0) {
                    // Trouver le prix selon le type d'élément
                    $service = Service::with('category')->findOrFail($serviceId);
                    $categoryName = $service->category->name;

                    // Variable pour stocker l'élément trouvé
                    $itemModel = null;

                    // Chercher l'élément dans la bonne table selon sa catégorie
                    switch ($categoryName) {
                        case 'menu':
                            $itemModel = \App\Models\MenuItem::findOrFail($itemId);
                            break;
                        case 'vetements':
                            $itemModel = \App\Models\Clothing::findOrFail($itemId);
                            break;
                        case 'negafa':
                            $itemModel = \App\Models\Negafa::findOrFail($itemId);
                            break;
                        case 'maquillage':
                            $itemModel = \App\Models\Makeup::findOrFail($itemId);
                            break;
                        case 'salles':
                            $itemModel = \App\Models\Salle::findOrFail($itemId);
                            break;
                        case 'decoration':
                            $itemModel = \App\Models\Decoration::findOrFail($itemId);
                            break;
                        case 'amariya':
                            $itemModel = \App\Models\Amariya::findOrFail($itemId);
                            break;
                        case 'animation':
                            $itemModel = \App\Models\Animation::findOrFail($itemId);
                            break;
                    }

                    if ($itemModel) {
                        $price = $itemModel->price;
                        $totalAmount += $price * $quantity;
                    }
                }
            }
        }

        // Utiliser une transaction pour garantir l'intégrité des données
        DB::beginTransaction();

        try {
            // Créer la réservation
            $reservation = Reservation::create([
                'mariee_id' => Auth::user()->mariee->id,
                'traiteur_id' => $request->traiteur_id,
                'event_date' => $request->event_date,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            // Enregistrer les services sélectionnés
            foreach ($request->items as $serviceId => $itemData) {
                foreach ($itemData as $itemId => $quantity) {
                    if ($quantity > 0) {
                        $service = Service::with('category')->findOrFail($serviceId);
                        $categoryName = $service->category->name;

                        $itemModel = null;
                        $itemType = null;

                        switch ($categoryName) {
                            case 'menu':
                                $itemModel = \App\Models\MenuItem::findOrFail($itemId);
                                $itemType = 'App\\Models\\MenuItem';
                                break;
                            case 'vetements':
                                $itemModel = \App\Models\Clothing::findOrFail($itemId);
                                $itemType = 'App\\Models\\Clothing';
                                break;
                            case 'negafa':
                                $itemModel = \App\Models\Negafa::findOrFail($itemId);
                                $itemType = 'App\\Models\\Negafa';
                                break;
                            case 'maquillage':
                                $itemModel = \App\Models\Makeup::findOrFail($itemId);
                                $itemType = 'App\\Models\\Makeup';
                                break;
                            case 'salles':
                                $itemModel = \App\Models\Salle::findOrFail($itemId);
                                $itemType = 'App\\Models\\Salle';
                                break;
                            case 'decoration':
                                $itemModel = \App\Models\Decoration::findOrFail($itemId);
                                $itemType = 'App\\Models\\Decoration';
                                break;
                            case 'amariya':
                                $itemModel = \App\Models\Amariya::findOrFail($itemId);
                                $itemType = 'App\\Models\\Amariya';
                                break;
                            case 'animation':
                                $itemModel = \App\Models\Animation::findOrFail($itemId);
                                $itemType = 'App\\Models\\Animation';
                                break;
                        }

                        if ($itemModel) {
                            // Créer un élément de réservation
                            ReservationService::create([
                                'reservation_id' => $reservation->id,
                                'service_id' => $serviceId,
                                'service_item_id' => $itemId,
                                'service_item_type' => $itemType,
                                'price' => $itemModel->price,
                                'quantity' => $quantity,
                            ]);
                        }
                    }
                }
            }

            // Marquer la date comme indisponible pour le traiteur
            $traiteur = Traiteur::findOrFail($request->traiteur_id);
            $traiteur->availabilities()->updateOrCreate(
                ['date' => $request->event_date],
                ['is_available' => false]
            );

            // Tout s'est bien passé, on valide la transaction
            DB::commit();

            return redirect()->route('reservations.summary', $reservation->id)
                ->with('success', 'Votre réservation a été enregistrée avec succès.');

        } catch (\Exception $e) {
            // En cas d'erreur, on annule la transaction
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de l\'enregistrement de la réservation: ' . $e->getMessage())
                ->withInput();
        }

    }


    public function summary($id)
    {
        $reservation = Reservation::with(['mariee', 'traiteur', 'services.service', 'services.serviceItem'])
                                  ->findOrFail($id);

        // Vérifier que la réservation appartient à l'utilisateur connecté
        if (Auth::user()->isMariee() && Auth::user()->mariee->id != $reservation->mariee_id) {
            return redirect()->route('welcome')
                ->with('error', 'Vous n\'êtes pas autorisé à accéder à cette réservation.');
        }

        return view('reservations.summary', compact('reservation'));
    }


    public function show($id)
    {
        $reservation = Reservation::with(['mariee.user', 'traiteur', 'services.service', 'services.serviceItem'])
                                  ->findOrFail($id);

        // Vérifier les autorisations
        if (Auth::user()->isMariee() && Auth::user()->mariee->id != $reservation->mariee_id) {
            return redirect()->route('welcome')
                ->with('error', 'Vous n\'êtes pas autorisé à accéder à cette réservation.');
        } elseif (Auth::user()->isTraiteur() && Auth::user()->traiteur->id != $reservation->traiteur_id) {
            return redirect()->route('welcome')
                ->with('error', 'Vous n\'êtes pas autorisé à accéder à cette réservation.');
        }

        return view('reservations.show', compact('reservation'));
    }


    public function cancel($id)
    {
        $reservation = Reservation::findOrFail($id);

        // Vérifier que la réservation appartient à l'utilisateur connecté
        if (Auth::user()->isMariee() && Auth::user()->mariee->id != $reservation->mariee_id) {
            return redirect()->route('welcome')
                ->with('error', 'Vous n\'êtes pas autorisé à annuler cette réservation.');
        }

        // Vérifier que la réservation peut être annulée
        if ($reservation->status != 'pending' && $reservation->status != 'confirmed') {
            return redirect()->route('reservations.show', $reservation->id)
                ->with('error', 'Cette réservation ne peut pas être annulée.');
        }

        $reservation->status = 'cancelled';
        $reservation->save();

        // Rendre la date à nouveau disponible pour le traiteur
        $traiteur = Traiteur::findOrFail($reservation->traiteur_id);
        $traiteur->availabilities()->updateOrCreate(
            ['date' => $reservation->event_date],
            ['is_available' => true]
        );

        return redirect()->route('reservations.index')
            ->with('success', 'La réservation a été annulée avec succès.');
    }


    



}
