@extends('layouts.app')

@section('title', 'Mon profil')

@section('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, #C08081 0%, #FADADD 100%);
        height: 120px;
        border-radius: 0.75rem 0.75rem 0 0;
        display: flex;
        align-items: center;
        padding: 0 2rem;
    }

    .profile-title {
        color: white;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .info-card {
        border-radius: 0.5rem;
        background-color: white;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
    }

    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .info-group {
        padding: 0.75rem 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .info-group:last-child {
        border-bottom: none;
    }

    .info-label {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-weight: 500;
        color: #111827;
    }

    .edit-button {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        background: white;
        color: #333333;
        border-radius: 9999px;
        padding: 0.5rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: all 0.2s;
    }

    .edit-button:hover {
        background: #f3f4f6;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12">
    <div class="container mx-auto px-4">
        @if(session('success'))
            <div class="max-w-4xl mx-auto mb-6">
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden relative">
            <!-- En-tête du profil -->
            <div class="profile-header">
                <h1 class="text-3xl font-bold profile-title font-display">Mon Profil</h1>

                <!-- Bouton d'édition -->
                <a href="{{ route('mariee.profile.edit') }}" class="edit-button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </a>
            </div>

            <div class="p-6">
                <!-- Nom du couple -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-[#333333] font-display mb-2">{{ Auth::user()->mariee->groom_name }} & {{ Auth::user()->mariee->bride_name }}</h2>
                    @if(isset(Auth::user()->mariee->wedding_date))
                        <p class="text-gray-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-[#C08081]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Mariage prévu le {{ \Carbon\Carbon::parse(Auth::user()->mariee->wedding_date)->format('d/m/Y') }}
                        </p>
                    @endif
                </div>

                <!-- Informations organisées en cartes -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Informations personnelles -->
                    <div class="info-card p-5">
                        <h3 class="text-xl font-semibold text-[#333333] mb-4 pb-2 border-b">Informations personnelles</h3>

                        <div class="info-group">
                            <p class="info-label">Nom du marié</p>
                            <p class="info-value">{{ Auth::user()->mariee->groom_name }}</p>
                        </div>

                        <div class="info-group">
                            <p class="info-label">Nom de la mariée</p>
                            <p class="info-value">{{ Auth::user()->mariee->bride_name }}</p>
                        </div>

                        <div class="info-group">
                            <p class="info-label">Email</p>
                            <p class="info-value">{{ Auth::user()->email }}</p>
                        </div>

                        <div class="info-group">
                            <p class="info-label">Ville</p>
                            <p class="info-value">{{ Auth::user()->mariee->city ?? 'Non spécifiée' }}</p>
                        </div>
                    </div>

                    <!-- Informations du mariage -->
                    <div class="info-card p-5">
                        <h3 class="text-xl font-semibold text-[#333333] mb-4 pb-2 border-b">Informations du mariage</h3>

                        <div class="info-group">
                            <p class="info-label">Date du mariage</p>
                            <p class="info-value">
                                @if(isset(Auth::user()->mariee->wedding_date))
                                    {{ \Carbon\Carbon::parse(Auth::user()->mariee->wedding_date)->format('d/m/Y') }}
                                @else
                                    Non spécifiée
                                @endif
                            </p>
                        </div>

                        @if(isset(Auth::user()->mariee->budget))
                        <div class="info-group">
                            <p class="info-label">Budget estimé</p>
                            <p class="info-value">{{ number_format(Auth::user()->mariee->budget, 0, ',', ' ') }} MAD</p>
                        </div>
                        @endif

                        <!-- Réservations -->
                        <div class="mt-4 bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-[#333333] mb-2">Vos réservations</h4>
                            <div class="grid grid-cols-3 gap-2">
                                <div class="text-center">
                                    <span class="block text-2xl font-bold text-[#C08081]">{{ Auth::user()->mariee->reservations->count() }}</span>
                                    <span class="text-xs text-gray-500">Total</span>
                                </div>
                                <div class="text-center">
                                    <span class="block text-2xl font-bold text-green-600">{{ Auth::user()->mariee->reservations->where('status', 'confirmed')->count() }}</span>
                                    <span class="text-xs text-gray-500">Confirmées</span>
                                </div>
                                <div class="text-center">
                                    <span class="block text-2xl font-bold text-yellow-500">{{ Auth::user()->mariee->reservations->where('status', 'pending')->count() }}</span>
                                    <span class="text-xs text-gray-500">En attente</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
