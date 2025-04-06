@extends('layouts.app')

@section('title', 'Planning de mariage')

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-3xl font-bold text-[#333333] mb-6 font-display">Planning de mariage</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(isset(Auth::user()->mariee->wedding_date))
                @php
                    $weddingDate = \Carbon\Carbon::parse(Auth::user()->mariee->wedding_date);
                    $daysRemaining = $weddingDate->diffInDays(now());
                @endphp

                <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                    <div class="flex items-center mb-4">
                        <div class="bg-[#FADADD]/20 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#C08081]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-[#333333]">Votre mariage est prévu le {{ $weddingDate->format('d/m/Y') }}</h2>
                            <p class="text-gray-600">Plus que {{ $daysRemaining }} jours avant le grand jour!</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                    <div class="flex items-center mb-4">
                        <div class="bg-[#FADADD]/20 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#C08081]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-[#333333]">Vous n'avez pas encore défini de date de mariage</h2>
                            <p class="text-gray-600">Mettez à jour votre profil pour définir une date de mariage.</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-semibold text-[#333333] mb-4">Tâches à accomplir</h2>

                <div class="space-y-4">
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <input type="checkbox" class="mr-3 h-5 w-5 text-[#FADADD] rounded focus:ring-[#FADADD]">
                        <span class="text-[#333333]">Définir un budget</span>
                    </div>

                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <input type="checkbox" class="mr-3 h-5 w-5 text-[#FADADD] rounded focus:ring-[#FADADD]">
                        <span class="text-[#333333]">Choisir un traiteur</span>
                    </div>

                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <input type="checkbox" class="mr-3 h-5 w-5 text-[#FADADD] rounded focus:ring-[#FADADD]">
                        <span class="text-[#333333]">Créer une liste d'invités</span>
                    </div>

                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <input type="checkbox" class="mr-3 h-5 w-5 text-[#FADADD] rounded focus:ring-[#FADADD]">
                        <span class="text-[#333333]">Envoyer les invitations</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
