@extends('layouts.app')

@section('title', 'Bientôt disponible')

@section('content')
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-2xl mx-auto text-center">
            <h1 class="text-4xl font-bold text-gray-800 mb-6">Bientôt disponible</h1>
            <p class="text-lg text-gray-600 mb-8">Cette page est en cours de développement et sera disponible prochainement.</p>
            <a href="{{ url('/') }}" class="inline-block px-6 py-3 rounded-md bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition duration-150">Retour à l'accueil</a>
        </div>
    </div>
@endsection
