<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Stripe\Stripe;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Stripe\Exception\ApiErrorException;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function show(Reservation $reservation){


        if (auth()->user()->mariee->id !== $reservation->mariee_id) {
            return redirect()->route('planning.index')
                ->with('error', 'Vous n\'êtes pas autorisé à accéder à cette page.');
        }

        return view('payment.show', compact('reservation'));

    }

    // Crée une session de paiement Stripe
    public function createSession(Request $request, Reservation $reservation){


        try {

        if (auth()->user()->mariee->id !== $reservation->mariee_id) {
            return redirect()->route('planning.index')
                ->with('error', 'Vous n\'êtes pas autorisé à accéder à cette page.');
        }

          // Vérifie si un paiement réussi existe déjà
          $existingPayment = Payment::where('reservation_id', $reservation->id)
                                  ->where('status', 'completed')
                                  ->first();
        if ($existingPayment)
              {
                return redirect()->route('payment.success', ['reservation' => $reservation->id])
                         ->with('info', 'Cette réservation a déjà été payée.');
              }

              $payment = Payment::create([
                'reservation_id' => $reservation->id,
                'amount' => $reservation->total_amount,
                'status' => 'pending',
                'payment_method' => 'stripe'
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.stripe.secret'),
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])->asForm()->post('https://api.stripe.com/v1/checkout/sessions', [
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Réservation de mariage #' . $reservation->id,
                            'description' => 'Services de mariage avec ' . $reservation->traiteur->manager_name,
                        ],
                        'unit_amount' => (int)round($reservation->total_amount * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payment.success', ['reservation' => $reservation->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payment.cancel', ['reservation' => $reservation->id]),
                'metadata' => [
                    'reservation_id' => $reservation->id,
                    'payment_id' => $payment->id
                ]
            ]);

              if ($response->failed()) {
                Log::error('Erreur API Stripe: ' . $response->body());
                return response()->json(['error' => 'Erreur de communication avec Stripe: ' . $response->body()], 500);
            }

             $sessionData = $response->json();

       // Met à jour le paiement avec l'ID de session Stripe
            $payment->update([
                'payment_details' => json_encode(['session_id' => $sessionData['id']])
            ]);

            // Renvoie l'ID de session au frontend
            return response()->json(['id' => $sessionData['id']]);
        }
        catch (\Exception $e) {
            // Log l'erreur
            return redirect()->route('planning.index')
            ->with('error', $e->getMessage());
            Log::error('Erreur Stripe: ' . $e->getMessage());

            // Renvoie l'erreur au frontend
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    // Gère le retour après un paiement réussi
    public function success(Request $request, Reservation $reservation)
{
    // Vérifier que l'utilisateur peut accéder à cette réservation
    if (auth()->user()->mariee->id !== $reservation->mariee_id) {
        return redirect()->route('planning.index')
            ->with('error', 'Vous n\'êtes pas autorisé à accéder à cette page.');
    }

    // Si on a un ID de session, on vérifie son statut
    if ($request->has('session_id')) {
        try {
            // Log pour le débogage
            Log::info('Vérification de la session: ' . $request->session_id);

            // Récupération des détails de la session via l'API Stripe
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.stripe.secret'),
            ])->get('https://api.stripe.com/v1/checkout/sessions/' . $request->session_id);

            if ($response->successful()) {
                $sessionData = $response->json();
                Log::info('Données de session Stripe: ' . json_encode($sessionData));

                // Récupère le paiement associé
                $payment = Payment::where('reservation_id', $reservation->id)
                            ->whereRaw("JSON_CONTAINS(payment_details, '\"" . $request->session_id . "\"', '$.session_id')")
                            ->first();

                // Si le paiement n'est pas trouvé, on vérifie tous les paiements pour cette réservation
                if (!$payment) {
                    Log::warning('Paiement non trouvé avec session_id exact, vérification des paiements de la réservation #' . $reservation->id);

                    // Récupérer tous les paiements en attente pour cette réservation
                    $payments = Payment::where('reservation_id', $reservation->id)
                                    ->where('status', 'pending')
                                    ->get();

                    Log::info('Paiements trouvés: ' . $payments->count());

                    // Utiliser le paiement le plus récent si disponible
                    if ($payments->count() > 0) {
                        $payment = $payments->sortByDesc('created_at')->first();
                        Log::info('Utilisation du paiement #' . $payment->id . ' (le plus récent)');
                    } else {
                        // Créer un nouveau paiement si aucun n'existe
                        $payment = Payment::create([
                            'reservation_id' => $reservation->id,
                            'amount' => $reservation->total_amount,
                            'status' => 'pending',
                            'payment_method' => 'stripe',
                            'payment_details' => json_encode(['session_id' => $request->session_id])
                        ]);
                        Log::info('Nouveau paiement créé #' . $payment->id);
                    }
                }

                if ($payment && isset($sessionData['payment_status']) && $sessionData['payment_status'] === 'paid') {
                    // Met à jour le statut du paiement
                    $payment->update([
                        'status' => 'completed',
                        'payment_id' => $sessionData['payment_intent'] ?? null,
                        'paid_at' => Carbon::now(),
                        'payment_details' => json_encode([
                            'session_id' => $sessionData['id'],
                            'payment_intent' => $sessionData['payment_intent'] ?? null
                        ])
                    ]);

                    // Met à jour le statut de la réservation
                    $reservation->update(['status' => 'confirmed']);

                    Log::info('Paiement #' . $payment->id . ' marqué comme complété');
                }
            } else {
                Log::error('Erreur lors de la récupération de la session Stripe: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Exception lors de la vérification de la session: ' . $e->getMessage());
            // Ne pas remonter l'erreur à l'utilisateur, continuer l'affichage
        }
    }

    return view('payment.success', compact('reservation'));
}


    // Gère le retour après annulation du paiement
    public function cancel(Reservation $reservation)
    {
        // Vérifier que l'utilisateur peut accéder à cette réservation
        if (auth()->user()->mariee->id !== $reservation->mariee_id) {
            return redirect()->route('planning.index')
                ->with('error', 'Vous n\'êtes pas autorisé à accéder à cette page.');
        }

        return view('payment.cancel', compact('reservation'));
    }

}
