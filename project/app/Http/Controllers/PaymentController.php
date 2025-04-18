<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $payementService ;

    public function __construct(PaymentService $payementService)
    {

     $this->middleware(['auth', 'verified']);
      $this->payementService = $payementService,
    }

    public function checkout($reservationId)
{
    $reservation = Reservation::with(['mariee.user', 'traiteur', 'services.service', 'services.serviceItem'])
                              ->findOrFail($reservationId);

    // Vérifier que la réservation appartient à l'utilisateur connecté
    if (Auth::user()->isMariee() && Auth::user()->mariee->id != $reservation->mariee_id) {
        return redirect()->route('welcome')
            ->with('error', 'Vous n\'êtes pas autorisé à payer cette réservation.');
    }

    // Vérifier que la réservation est en attente de paiement
    if ($reservation->status != 'pending') {
        return redirect()->route('reservations.show', $reservation->id)
            ->with('error', 'Cette réservation ne peut pas être payée actuellement.');
    }

    try {
        // Utiliser le service pour créer la session de paiement
        $session = $this->paymentService->createCheckoutSession($reservation);

        // Rediriger vers la page de paiement Stripe
        return redirect($session->url);

    } catch (\Exception $e) {
        return redirect()->route('reservations.show', $reservation->id)
            ->with('error', 'Erreur lors de la création du paiement: ' . $e->getMessage());
    }
}

/**
 * Traite le succès du paiement
 */
public function success(Request $request)
{
    $request->validate([
        'reservation_id' => 'required|exists:reservations,id',
        'session_id' => 'required|string',
    ]);

    $reservation = Reservation::findOrFail($request->reservation_id);

    // Vérifier que la réservation appartient à l'utilisateur connecté
    if (Auth::user()->isMariee() && Auth::user()->mariee->id != $reservation->mariee_id) {
        return redirect()->route('welcome')
            ->with('error', 'Vous n\'êtes pas autorisé à payer cette réservation.');
    }

    try {
        // Utiliser le service pour traiter le paiement
        $payment = $this->paymentService->processSuccessfulPayment($reservation, $request->session_id);

        return redirect()->route('payments.invoice', $payment->id)
            ->with('success', 'Paiement effectué avec succès. Votre réservation est confirmée.');

    } catch (\Exception $e) {
        return redirect()->route('reservations.show', $reservation->id)
            ->with('error', 'Erreur lors de la vérification du paiement: ' . $e->getMessage());
    }
}

/**
 * Génère et affiche la facture
 */
public function invoice($paymentId)
{
    $payment = Payment::with(['reservation.mariee.user', 'reservation.traiteur', 'reservation.services.service', 'reservation.services.serviceItem'])
                       ->findOrFail($paymentId);

    // Vérifier les permissions
    if (Auth::user()->isMariee() && Auth::user()->mariee->id != $payment->reservation->mariee_id) {
        return redirect()->route('welcome')
            ->with('error', 'Vous n\'êtes pas autorisé à accéder à cette facture.');
    } elseif (Auth::user()->isTraiteur() && Auth::user()->traiteur->id != $payment->reservation->traiteur_id) {
        return redirect()->route('welcome')
            ->with('error', 'Vous n\'êtes pas autorisé à accéder à cette facture.');
    }

    return view('payments.invoice', compact('payment'));
}

/**
 * Télécharge la facture en PDF
 */
public function downloadInvoice($paymentId)
{
    $payment = Payment::with(['reservation.mariee.user', 'reservation.traiteur', 'reservation.services.service', 'reservation.services.serviceItem'])
                       ->findOrFail($paymentId);

    // Vérifier les permissions
    if (Auth::user()->isMariee() && Auth::user()->mariee->id != $payment->reservation->mariee_id) {
        return redirect()->route('welcome')
            ->with('error', 'Vous n\'êtes pas autorisé à accéder à cette facture.');
    } elseif (Auth::user()->isTraiteur() && Auth::user()->traiteur->id != $payment->reservation->traiteur_id) {
        return redirect()->route('welcome')
            ->with('error', 'Vous n\'êtes pas autorisé à accéder à cette facture.');
    }

    // Utiliser le service pour générer le PDF
    $pdf = $this->paymentService->generateInvoicePdf($payment);

    return $pdf->download('facture-' . $payment->id . '.pdf');
}

}
