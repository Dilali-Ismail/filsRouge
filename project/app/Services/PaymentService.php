<?php

namespace App\Services;

use PDF;
use Stripe\Stripe;
use App\Models\Payment;
use App\Models\Reservation;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;

class PaymentService{

public function __construct(){

    Stripe::setApiKey(config('services.stripe.secret'));

}


public function createCheckoutSession(Reservation $reservation)
    {
        try {
            return Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'mad',
                            'product_data' => [
                                'name' => 'Réservation de mariage - ' . $reservation->traiteur->manager_name,
                                'description' => 'Date: ' . $reservation->event_date->format('d/m/Y'),
                            ],
                            'unit_amount' => (int)($reservation->total_amount * 100),
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => route('payments.success', ['reservation_id' => $reservation->id, 'session_id' => '{CHECKOUT_SESSION_ID}']),
                'cancel_url' => route('reservations.show', $reservation->id),
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function processSuccessfulPayment(Reservation $reservation, string $sessionId)
    {
        try {
            // Récupérer les détails de la session
            $session = Session::retrieve($sessionId);

            // Créer l'enregistrement de paiement
            $payment = Payment::create([
                'reservation_id' => $reservation->id,
                'payment_id' => $session->id,
                'amount' => $session->amount_total / 100, // Convertir de centimes en dirham
                'status' => $session->payment_status == 'paid' ? 'completed' : 'pending',
                'payment_method' => 'stripe',
                'payment_details' => json_encode($session),
                'paid_at' => now(),
            ]);

            // Mettre à jour le statut de la réservation
            $reservation->status = 'confirmed';
            $reservation->save();

            return $payment;
        } catch (ApiErrorException $e) {
            Log::error('Stripe verification error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function generateInvoicePdf(Payment $payment)
    {
        // Logique pour générer un PDF (nécessite une bibliothèque comme DomPDF)
        $pdf = PDF::loadView('payments.invoice-pdf', ['payment' => $payment]);
        return $pdf;
    }




}

