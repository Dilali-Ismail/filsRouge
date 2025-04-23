<?php

namespace App\Http\Controllers;

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
use App\Models\ReservationService;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function store(Request $request, $traiteurId)
{
    // Validation des données
    $request->validate([
        'event_date' => 'required|date_format:Y-m-d',
        'services_json' => 'required|json',
        'nombre_invites' => 'required|integer|min:1',
        'nombre_tables' => 'required|integer|min:1',
    ]);

    // Récupérer le traiteur
    $traiteur = Traiteur::findOrFail($traiteurId);

    // Décoder les services JSON
    $services = json_decode($request->services_json, true);

    // Calculer le montant total
    $totalAmount = 0;
    foreach ($services as $service) {
        $totalAmount += floatval($service['price']);
    }

    // Créer la réservation
    $reservation = Reservation::create([
        'mariee_id' => Auth::user()->mariee->id,
        'traiteur_id' => $traiteur->id,
        'event_date' => $request->event_date,
        'nombre_invites' => $request->nombre_invites,
        'nombre_tables' => $request->nombre_tables,
        'total_amount' => $totalAmount,
        'status' => 'pending'
    ]);

    // Enregistrer chaque service de la réservation
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

            // Répéter pour tous les autres types de services...
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

        // Enregistrer le service de réservation
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

    // Rediriger vers la page de paiement
    return redirect()->route('payment.show', ['reservation' => $reservation->id])
                     ->with('success', 'Votre réservation a été enregistrée. Veuillez procéder au paiement.');
}



}
