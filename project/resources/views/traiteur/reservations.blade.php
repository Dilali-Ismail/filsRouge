@extends('layouts.app')

@section('title', 'Mes réservations')

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-3xl font-bold text-[#333333] mb-6 font-display">Mes réservations</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold text-[#333333] mb-4">Réservations en cours</h2>

                <p class="text-gray-600">Vous n'avez aucune réservation en cours.</p>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-semibold text-[#333333] mb-4">Historique des réservations</h2>

                <p class="text-gray-600">Aucun historique de réservation à afficher.</p>
            </div>
        </div>
    </div>
</div>
@endsection
