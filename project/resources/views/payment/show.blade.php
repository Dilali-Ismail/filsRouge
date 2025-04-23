@extends('layouts.app')

@section('title', 'Paiement')

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-[#333333] mb-6 font-display">Paiement de votre réservation</h1>

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Récapitulatif de la réservation -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold text-[#333333] mb-4">Récapitulatif de votre réservation</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Traiteur</p>
                        <p class="font-medium text-[#333333] mb-4">{{ $reservation->traiteur->manager_name }}</p>

                        <p class="text-sm text-gray-600 mb-1">Date de l'événement</p>
                        <p class="font-medium text-[#333333] mb-4">{{ \Carbon\Carbon::parse($reservation->event_date)->format('d/m/Y') }}</p>

                        <p class="text-sm text-gray-600 mb-1">Nombre d'invités</p>
                        <p class="font-medium text-[#333333] mb-4">{{ $reservation->nombre_invites }} ({{ $reservation->nombre_tables }} tables)</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-1">Statut de la réservation</p>
                        <p class="font-medium mb-4">
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">
                                {{ $reservation->status === 'pending' ? 'En attente de paiement' : $reservation->status }}
                            </span>
                        </p>

                        <p class="text-sm text-gray-600 mb-1">Montant total</p>
                        <p class="font-medium text-[#C08081] text-xl mb-4">{{ number_format($reservation->total_amount, 2) }} MAD</p>
                    </div>
                </div>
            </div>

            <!-- Bouton de paiement Stripe -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold text-[#333333] mb-4">Procéder au paiement</h2>

                <p class="text-gray-600 mb-6">
                    Cliquez sur le bouton ci-dessous pour procéder au paiement sécurisé avec Stripe.
                </p>

                <div class="text-center">
                    <button
                        id="checkout-button"
                        class="bg-[#FADADD] hover:bg-[#C08081] text-[#333333] hover:text-white font-medium py-3 px-6 rounded-lg transition duration-300"
                    >
                        Payer {{ number_format($reservation->total_amount, 2) }} MAD
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script Stripe -->
<script src="https://js.stripe.com/v3/"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stripe = Stripe('{{ config('services.stripe.key') }}');
        const checkoutButton = document.getElementById('checkout-button');

        checkoutButton.addEventListener('click', function() {
            // Désactiver le bouton pendant le chargement
            checkoutButton.disabled = true;
            checkoutButton.textContent = 'Chargement...';

            // Créer une session de paiement
            fetch('{{ route('payment.create.session', ['reservation' => $reservation->id]) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({}) // Corps vide mais nécessaire pour une requête POST
            })
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('La requête a échoué avec le statut: ' + response.status);
                }
                return response.json();
            })
            .then(function(session) {
                if (session.error) {
                    throw new Error(session.error);
                }

                // Rediriger vers Checkout
                return stripe.redirectToCheckout({ sessionId: session.id });
            })
            .then(function(result) {
                if (result && result.error) {
                    throw new Error(result.error.message);
                }
            })
            .catch(function(error) {
                console.error('Erreur détaillée:', error);
                alert('Une erreur est survenue lors de la création de la session de paiement: ' + error.message);
                checkoutButton.disabled = false;
                checkoutButton.textContent = 'Payer {{ number_format($reservation->total_amount, 2) }} MAD';
            });
        });
    });
</script>
@endsection
