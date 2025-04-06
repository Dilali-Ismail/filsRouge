@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-8">
            <h1 class="text-3xl font-bold text-[#333333] mb-6 font-display">Mon profil professionnel</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h2 class="text-xl font-semibold text-[#333333] mb-4">Informations de l'entreprise</h2>

                    <div class="mb-4">
                        <p class="text-sm text-gray-600">Nom du responsable</p>
                        <p class="font-medium text-[#333333]">{{ Auth::user()->traiteur->manager_name }}</p>
                    </div>

                    <div class="mb-4">
                        <p class="text-sm text-gray-600">Numéro d'immatriculation</p>
                        <p class="font-medium text-[#333333]">{{ Auth::user()->traiteur->registration_number }}</p>
                    </div>

                    <div class="mb-4">
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-medium text-[#333333]">{{ Auth::user()->email }}</p>
                    </div>

                    <div class="mb-4">
                        <p class="text-sm text-gray-600">Téléphone</p>
                        <p class="font-medium text-[#333333]">{{ Auth::user()->traiteur->phone_number }}</p>
                    </div>

                    <div class="mb-4">
                        <p class="text-sm text-gray-600">Ville</p>
                        <p class="font-medium text-[#333333]">{{ Auth::user()->traiteur->city }}</p>
                    </div>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-[#333333] mb-4">Logo</h2>

                    @if(Auth::user()->traiteur->logo)
                        <img src="{{ asset('storage/' . Auth::user()->traiteur->logo) }}" alt="Logo" class="w-32 h-32 object-contain border rounded-lg">
                    @else
                        <div class="w-32 h-32 bg-gray-100 flex items-center justify-center rounded-lg text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif

                    <div class="mt-6">
                        <a href="#" class="px-4 py-2 bg-[#FADADD] hover:bg-[#C08081] text-[#333333] hover:text-white rounded-lg transition duration-300">
                            Modifier mon profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
