@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-3xl font-bold text-[#333333] mb-6 font-display">Dashboard Administrateur</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Statistiques - ces valeurs sont fictives, vous devrez les remplacer par des données réelles -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-[#333333] mb-4">Mariées</h3>
                    <p class="text-3xl font-bold text-[#C08081]">0</p>
                    <p class="text-gray-600">comptes actifs</p>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-[#333333] mb-4">Traiteurs</h3>
                    <p class="text-3xl font-bold text-[#C08081]">0</p>
                    <p class="text-gray-600">comptes actifs</p>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-[#333333] mb-4">Messages</h3>
                    <p class="text-3xl font-bold text-[#C08081]">0</p>
                    <p class="text-gray-600">échangés ce mois-ci</p>
                </div>
            </div>

            <!-- Liste des traiteurs en attente de validation -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold text-[#333333] mb-4">Traiteurs en attente de validation</h2>

                <p class="text-gray-600">Aucun traiteur en attente de validation.</p>
            </div>

            <!-- Autres sections d'administration -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-semibold text-[#333333] mb-4">Actions rapides</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="#" class="bg-[#FADADD]/10 hover:bg-[#FADADD]/20 p-4 rounded-lg flex items-center transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#C08081] mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span class="text-[#333333]">Créer un template d'invitation</span>
                    </a>

                    <a href="#" class="bg-[#FADADD]/10 hover:bg-[#FADADD]/20 p-4 rounded-lg flex items-center transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#C08081] mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span class="text-[#333333]">Voir les statistiques</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
