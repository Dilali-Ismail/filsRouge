@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12 md:py-20">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row max-w-5xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Image côté gauche -->
            <div class="md:w-1/2 bg-cover bg-center h-64 md:h-auto" style="background-image: url('{{ asset('images/login-image.jpg') }}')">
                <!-- L'image est définie en CSS comme arrière-plan pour faciliter sa modification -->
            </div>

            <!-- Formulaire côté droit -->
            <div class="md:w-1/2 p-8 md:p-12">
                <div class="mb-8 text-center">
                    <h1 class="text-3xl font-bold text-[#333333] mb-2 font-display">Bienvenue !</h1>
                    <p class="text-gray-600">Heureux de vous revoir. Connectez-vous pour accéder à votre compte.</p>
                </div>

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-[#333333] mb-2">Adresse email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]">

                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mot de passe -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-[#333333] mb-2">Mot de passe</label>
                        <input id="password" type="password" name="password" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]">

                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Se souvenir de moi -->
                    <div class="mb-6 flex items-center">
                        <input id="remember" type="checkbox" name="remember" class="rounded text-[#FADADD] focus:ring-[#FADADD]">
                        <label for="remember" class="ml-2 text-sm text-[#333333]">Se souvenir de moi</label>
                    </div>

                    <!-- Bouton de connexion -->
                    <div class="mb-6">
                        <button type="submit" class="w-full bg-[#FADADD] hover:bg-[#C08081] text-[#333333] hover:text-white font-medium py-3 px-4 rounded-lg transition duration-300 shadow-md hover:shadow-lg">
                            Se connecter
                        </button>
                    </div>

                    <!-- Lien d'inscription -->
                    <div class="text-center">
                        <p class="text-[#333333]">
                            Vous n'avez pas de compte ?
                            <a href="{{ route('register') }}" class="text-[#C08081] hover:underline font-medium">S'inscrire</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
