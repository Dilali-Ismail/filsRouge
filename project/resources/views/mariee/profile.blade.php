@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-8">
            <h1 class="text-3xl font-bold text-[#333333] mb-6 font-display">Mon profil</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h2 class="text-xl font-semibold text-[#333333] mb-4">Informations personnelles</h2>

                    <div class="mb-4">
                        <p class="text-sm text-gray-600">Nom du marié</p>
                        <p class="font-medium text-[#333333]">{{ Auth::user()->mariee->groom_name }}</p>
                    </div>

                    <div class="mb-4">
                        <p class="text-sm text-gray-600">Nom de la mariée</p>
                        <p class="font-medium text-[#333333]">{{ Auth::user()->mariee->bride_name }}</p>
                    </div>

                    <div class="mb-4">
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-medium text-[#333333]">{{ Auth::user()->email }}</p>
                    </div>

                    <div class="mb-4">
                        <p class="text-sm text-gray-600">Ville</p>
                        <p class="font-medium text-[#333333]">{{ Auth::user()->mariee->city ?? 'Non spécifiée' }}</p>
                    </div>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-[#333333] mb-4">Informations du mariage</h2>

                    <div class="mb-4">
                        <p class="text-sm text-gray-600">Date du mariage</p>
                        <p class="font-medium text-[#333333]">
                            @if(isset(Auth::user()->mariee->wedding_date))
                                {{ \Carbon\Carbon::parse(Auth::user()->mariee->wedding_date)->format('d/m/Y') }}
                            @else
                                Non spécifiée
                            @endif
                        </p>
                    </div>

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
