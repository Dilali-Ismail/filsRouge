@extends('layouts.app')

@section('title', 'Modifier mon profil')

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold text-[#333333] font-display">Modifier mon profil</h1>
                <a href="{{ route('mariee.profile') }}" class="text-[#C08081] hover:underline flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour au profil
                </a>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-md">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p>Veuillez corriger les erreurs suivantes :</p>
                    </div>
                    <ul class="list-disc ml-10 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('mariee.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h2 class="text-xl font-semibold text-[#333333] mb-4 border-b pb-2">Informations personnelles</h2>

                        <div class="mb-4">
                            <label for="groom_name" class="block text-sm font-medium text-gray-700 mb-1">Nom du marié</label>
                            <input type="text" name="groom_name" id="groom_name" value="{{ old('groom_name', Auth::user()->mariee->groom_name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]">
                        </div>

                        <div class="mb-4">
                            <label for="bride_name" class="block text-sm font-medium text-gray-700 mb-1">Nom de la mariée</label>
                            <input type="text" name="bride_name" id="bride_name" value="{{ old('bride_name', Auth::user()->mariee->bride_name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]">
                        </div>

                        <div class="mb-4">
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                            <input type="text" name="city" id="city" value="{{ old('city', Auth::user()->mariee->city) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]">
                        </div>
                    </div>

                    <div>
                        <h2 class="text-xl font-semibold text-[#333333] mb-4 border-b pb-2">Informations du mariage</h2>

                        <div class="mb-4">
                            <label for="wedding_date" class="block text-sm font-medium text-gray-700 mb-1">Date du mariage</label>
                            <input type="date" name="wedding_date" id="wedding_date" value="{{ old('wedding_date', Auth::user()->mariee->wedding_date ? Auth::user()->mariee->wedding_date->format('Y-m-d') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]">
                        </div>

                        <div class="mb-4">
                            <label for="budget" class="block text-sm font-medium text-gray-700 mb-1">Budget estimé (MAD)</label>
                            <input type="number" name="budget" id="budget" value="{{ old('budget', Auth::user()->mariee->budget) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]">
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <a href="{{ route('mariee.profile') }}" class="px-5 py-2 border border-gray-300 rounded-lg mr-4 text-gray-700 hover:bg-gray-50 transition">
                        Annuler
                    </a>
                    <button type="submit" class="px-5 py-2 bg-[#FADADD] hover:bg-[#C08081] text-[#333333] hover:text-white rounded-lg transition">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
