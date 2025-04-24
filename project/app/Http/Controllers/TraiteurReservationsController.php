<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Reservation;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF;
use App\Models\ReservationService;
use Illuminate\Support\Facades\DB;
use App\Models\TraiteurAvailability;
use Illuminate\Support\Facades\Auth;

class TraiteurReservationsController extends Controller
{
    public function index(){
       // Vérifier que l'utilisateur est un traiteur
       if (!Auth::user()->isTraiteur()) {
        return redirect()->route('welcome')->with('error', 'Accès non autorisé.');
    }

    $traiteurId = Auth::user()->traiteur->id;

    // Récupérer les réservations confirmées (payées)
    $reservations = Reservation::where('traiteur_id', $traiteurId)
        ->where('status', 'confirmed')
        ->orderBy('event_date', 'desc')
        ->with('mariee.user')
        ->get();

    // Récupérer les dates d'indisponibilité
    $unavailableDates = TraiteurAvailability::where('traiteur_id', $traiteurId)
        ->where('is_available', false)
        ->pluck('date')
        ->map(function($date) {
            return Carbon::parse($date)->format('Y-m-d');
        })
        ->toArray();

    // Récupérer les dates des réservations (également indisponibles)
    $reservedDates = Reservation::where('traiteur_id', $traiteurId)
        ->where('status', '!=', 'cancelled')
        ->pluck('event_date')
        ->map(function($date) {
            return Carbon::parse($date)->format('Y-m-d');
        })
        ->toArray();

    // Combiner les deux tableaux
    $disabledDates = array_unique(array_merge($unavailableDates, $reservedDates));

    return view('traiteur.reservations', [
        'reservations' => $reservations,
        'disabledDates' => json_encode($disabledDates),
        'reservedDates' => $reservedDates // Ajout de cette variable manquante
    ]);
    }

    public function downloadPdf(Reservation $reservation, PdfService $pdfService)
{
    // Vérifier que l'utilisateur est le traiteur associé à cette réservation
    if (!Auth::user()->isTraiteur() || Auth::user()->traiteur->id !== $reservation->traiteur_id) {
        return redirect()->route('welcome')->with('error', 'Accès non autorisé.');
    }

    // Charger les relations nécessaires
    $reservation->load('mariee.user', 'traiteur.user');

    // Récupérer tous les services réservés avec leurs détails
    $reservationServices = ReservationService::where('reservation_id', $reservation->id)
        ->with('service.category')
        ->get();

    // Tableau pour stocker les services organisés par catégorie
    $organizedServices = [];

    foreach ($reservationServices as $reservationService) {
        $service = $reservationService->service;

        if (!$service || !$service->category) {
            continue; // Ignorer si le service ou sa catégorie n'existe pas
        }

        $serviceCategory = $service->category->name;

        // Récupérer les détails spécifiques selon le type de service
        $serviceDetails = null;

        // Si un élément spécifique a été sélectionné
        if ($reservationService->service_item_id && $reservationService->service_item_type) {
            $itemType = $reservationService->service_item_type;
            $itemId = $reservationService->service_item_id;

            // Correction pour les noms de modèles
            $modelMapping = [
                'App\\Models\\ClothingItem' => 'App\\Models\\Clothing',
                // Ajoutez d'autres mappings si nécessaire
            ];

            // Remplacer le type si nécessaire
            if (array_key_exists($itemType, $modelMapping)) {
                $itemType = $modelMapping[$itemType];
            }

            try {
                // Vérifier si la classe existe
                if (class_exists($itemType)) {
                    // Charger l'élément spécifique selon son type
                    $specificItem = $itemType::find($itemId);

                    if ($specificItem) {
                        switch ($serviceCategory) {
                            case 'menu':
                                $serviceDetails = [
                                    'nom' => $specificItem->name,
                                    'catégorie' => $specificItem->category ?? 'Non spécifiée',
                                    'prix' => $reservationService->price
                                ];
                                break;

                            case 'vetements':
                                $serviceDetails = [
                                    'nom' => $specificItem->name,
                                    'style' => $specificItem->style ?? 'Non spécifié',
                                    'catégorie' => $specificItem->category ?? 'Non spécifiée',
                                    'prix' => $reservationService->price
                                ];
                                break;

                            default:
                                // Pour les autres types de services (négafa, maquillage, etc.)
                                $serviceDetails = [
                                    'nom' => $specificItem->name ?? 'Non spécifié',
                                    'prix' => $reservationService->price
                                ];
                                break;
                        }
                    }
                }
            } catch (\Exception $e) {
                // En cas d'erreur, on utilise les informations du service parent
                $serviceDetails = [
                    'nom' => $service->title . ' (détail non disponible)',
                    'prix' => $reservationService->price
                ];
            }
        }

        // Si aucun détail spécifique n'a été trouvé, utiliser les informations du service
        if (!$serviceDetails) {
            $serviceDetails = [
                'nom' => $service->title,
                'prix' => $reservationService->price
            ];
        }

        // Ajouter le service à la catégorie appropriée
        if (!isset($organizedServices[$serviceCategory])) {
            $organizedServices[$serviceCategory] = [];
        }

        $organizedServices[$serviceCategory][] = $serviceDetails;
    }

    // Générer et retourner le PDF
    return $pdfService->generatePdf('traiteur.pdf.reservation', [
        'reservation' => $reservation,
        'organizedServices' => $organizedServices
    ], 'reservation-' . $reservation->id . '.pdf');
}


}
