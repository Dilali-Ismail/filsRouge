@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
    <div class="container mx-auto px-4 py-12">

        <div class="relative bg-gray-900 overflow-hidden">
            <!-- Image de fond avec overlay, plus grande maintenant -->
            <div class="absolute inset-0 -m-8">
                <img src="{{ asset('images/hero-section.jpg') }}" alt="Mariage marocain" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-br from-black/60 to-black/30"></div>
            </div>

            <!-- Contenu du Hero -->
            <div class="relative px-4 py-40 sm:px-6 sm:py-48 lg:py-64 lg:px-8">
                <div class="max-w-3xl mx-auto text-center">
                    <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl font-display">
                        Bienvenue sur Alf Mabrouk
                    </h1>
                    <p class="mt-6 text-xl text-white font-light">
                        La plateforme qui simplifie l'organisation de votre mariage
                    </p>
                    <div class="mt-12 flex justify-center">
                        <div class="inline-flex rounded-md shadow">
                            <a href="{{ url('/planning') }}" class="inline-flex items-center justify-center px-6 py-4 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 transition duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                Commencer
                            </a>
                        </div>
                        <div class="ml-4 inline-flex rounded-md shadow">
                            <a href="{{ url('/services') }}" class="inline-flex items-center justify-center px-6 py-4 border border-transparent text-base font-medium rounded-lg text-indigo-600 bg-white hover:bg-gray-50 transition duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                Nos Services
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Autres sections à ajouter ultérieurement -->

        <!-- Section "Comment ça marche" -->
       <!-- Section "Comment ça marche" avec style inspiré de Bridebook -->
<div class="bg-white py-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 mb-4">Comment ça marche</h2>
            <div class="w-24 h-1 bg-indigo-600 mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 max-w-6xl mx-auto">
            <!-- Étape 1 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl font-bold text-indigo-600">1</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Créer un compte</h3>
                <p class="text-gray-600">Inscrivez-vous en tant que futur marié pour accéder à toutes les fonctionnalités.</p>
            </div>

            <!-- Étape 2 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl font-bold text-indigo-600">2</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Choisir un traiteur</h3>
                <p class="text-gray-600">Parcourez notre liste de traiteurs partenaires et sélectionnez celui qui vous convient.</p>
            </div>

            <!-- Étape 3 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl font-bold text-indigo-600">3</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Planifiez et réservez</h3>
                <p class="text-gray-600">Réservez vos services et gérez votre planning de mariage facilement en ligne.</p>
            </div>
        </div>
    </div>
</div>

    </div>
@endsection
