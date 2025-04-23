@extends('layouts.app')

@section('title', 'Paiement réussi')

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-8">
            <div class="text-center mb-8">
                <div class="bg-green-100 rounded-full h-20 w-20 flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-green-600 mb-3 font-display">Paiement réussi !</h1>
                <p class="text-gray-600 text-lg mb-6">
                    Merci ! Votre réservation a été confirmée.
                </p>
            </div>

            <div class="bg-gray-50 rounded-lg p-6 mb-8">
                <h2 class="text-lg font-semibold text-[#333333] mb-4">Récapitulatif de la réservation</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Numéro de réservation</p>
                        <p class="font-medium text-[#333333] mb-3">#{{ $reservation->id }}</p>

                        <p class="text-sm text-gray-600 mb-1">Traiteur</p>
                        <p class="font-medium text-[#333333] mb-3">{{ $reservation->traiteur->manager_name }}</p>

                        <p class="text-sm text-gray-600 mb-1">Date de l'événement</p>
                        <p class="font-medium text-[#333333] mb-3">{{ \Carbon\Carbon::parse($reservation->event_date)->format('d/m/Y') }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-1">Nombre d'invités</p>
                        <p class="font-medium text-[#333333] mb-3">{{ $reservation->nombre_invites }} ({{ $reservation->nombre_tables }} tables)</p>

                        <p class="text-sm text-gray-600 mb-1">Statut</p>
                        <p class="font-medium mb-3">
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                Payé
                            </span>
                        </p>

                        <p class="text-sm text-gray-600 mb-1">Montant payé</p>
                        <p class="font-medium text-[#C08081] mb-3">{{ number_format($reservation->total_amount, 2) }} MAD</p>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('planning.index') }}" class="bg-[#FADADD] hover:bg-[#C08081] text-[#333333] hover:text-white font-medium py-3 px-6 rounded-lg transition duration-300 inline-block">
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
