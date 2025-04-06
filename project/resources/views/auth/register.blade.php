@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12 md:py-20">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row max-w-6xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Image côté gauche -->
            <div class="md:w-2/5 bg-cover bg-center h-64 md:h-auto" style="background-image: url('{{ asset('images/register-image.jpg') }}')">
                <!-- L'image est définie en CSS comme arrière-plan pour faciliter sa modification -->
            </div>

            <!-- Formulaire côté droit -->
            <div class="md:w-3/5 p-8 md:p-12">
                <div class="mb-8 text-center">
                    <h1 class="text-3xl font-bold text-[#333333] mb-2 font-display">Créez votre compte</h1>
                    <p class="text-gray-600">Rejoignez-nous pour simplifier l'organisation de votre mariage</p>
                </div>

                <!-- Boutons de switch entre formulaires -->
                <div class="mb-8">
                    <div class="flex rounded-lg bg-gray-100 p-1 mb-6">
                        <button type="button" id="btn-couple" class="flex-1 py-3 px-4 rounded-lg font-medium transition-all duration-300 focus:outline-none bg-[#FADADD] text-[#333333] shadow-sm">
                            Futur(e) marié(e)
                        </button>
                        <button type="button" id="btn-caterer" class="flex-1 py-3 px-4 rounded-lg font-medium transition-all duration-300 focus:outline-none text-gray-500 hover:text-[#333333]">
                            Traiteur
                        </button>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Champ caché pour le rôle -->
                    <input type="hidden" id="role_id" name="role_id" value="{{ old('role_id', '1') }}">

                    <!-- Email et mot de passe (commun aux deux formulaires) -->
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-[#333333] mb-2">Adresse email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]">

                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-[#333333] mb-2">Mot de passe</label>
                            <input id="password" type="password" name="password" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]">

                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-[#333333] mb-2">Confirmation du mot de passe</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]">
                        </div>
                    </div>

                    <!-- Formulaire pour les mariés -->
                    <div id="couple_form" class="transition-all duration-500">
                        <div class="mb-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="groom_name" class="block text-sm font-medium text-[#333333] mb-2">Nom du marié</label>
                                    <input id="groom_name" type="text" name="groom_name" value="{{ old('groom_name') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]">

                                    @error('groom_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="bride_name" class="block text-sm font-medium text-[#333333] mb-2">Nom de la mariée</label>
                                    <input id="bride_name" type="text" name="bride_name" value="{{ old('bride_name') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]">

                                    @error('bride_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>



                                <div>
                                    <label for="city" class="block text-sm font-medium text-[#333333] mb-2">Ville</label>
                                    <input id="city" type="text" name="city" value="{{ old('city') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]">

                                    @error('city')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="wedding_date" class="block text-sm font-medium text-[#333333] mb-2">Date prévue du mariage (optionnel)</label>
                                    <input id="wedding_date" type="date" name="wedding_date" value="{{ old('wedding_date') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]">

                                    @error('wedding_date')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire pour les traiteurs -->
                    <div id="caterer_form" class="hidden transition-all duration-500">
                        <div class="mb-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="manager_name" class="block text-sm font-medium text-[#333333] mb-2">Nom du responsable</label>
                                    <input id="manager_name" type="text" name="manager_name" value="{{ old('manager_name') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]">

                                    @error('manager_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="registration_number" class="block text-sm font-medium text-[#333333] mb-2">Numéro d'immatriculation</label>
                                    <input id="registration_number" type="text" name="registration_number" value="{{ old('registration_number') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]">

                                    @error('registration_number')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone_number" class="block text-sm font-medium text-[#333333] mb-2">Numéro de téléphone</label>
                                    <input id="phone_number" type="text" name="phone_number" value="{{ old('phone_number') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]">

                                    @error('phone_number')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="city" class="block text-sm font-medium text-[#333333] mb-2">Ville</label>
                                    <input id="city" type="text" name="city" value="{{ old('city') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]">

                                    @error('city')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bouton d'inscription -->
                    <div class="mb-6">
                        <button type="submit" class="w-full bg-[#FADADD] hover:bg-[#C08081] text-[#333333] hover:text-white font-medium py-3 px-4 rounded-lg transition duration-300 shadow-md hover:shadow-lg">
                            S'inscrire
                        </button>
                    </div>

                    <!-- Lien de connexion -->
                    <div class="text-center">
                        <p class="text-[#333333]">
                            Vous avez déjà un compte ?
                            <a href="{{ route('login') }}" class="text-[#C08081] hover:underline font-medium">Se connecter</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script pour le switch entre formulaires -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnCouple = document.getElementById('btn-couple');
        const btnCaterer = document.getElementById('btn-caterer');
        const coupleForm = document.getElementById('couple_form');
        const catererForm = document.getElementById('caterer_form');
        const roleIdInput = document.getElementById('role_id');

        // Switch vers le formulaire couple
        btnCouple.addEventListener('click', function() {
            // Mise à jour des styles des boutons
            btnCouple.classList.add('bg-[#FADADD]', 'text-[#333333]', 'shadow-sm');
            btnCouple.classList.remove('text-gray-500');
            btnCaterer.classList.remove('bg-[#FADADD]', 'text-[#333333]', 'shadow-sm');
            btnCaterer.classList.add('text-gray-500');

            // Affichage du bon formulaire
            coupleForm.classList.remove('hidden');
            catererForm.classList.add('hidden');

            // Mise à jour du rôle (1 = mariee)
            roleIdInput.value = '1';
        });

        // Switch vers le formulaire traiteur
        btnCaterer.addEventListener('click', function() {
            // Mise à jour des styles des boutons
            btnCaterer.classList.add('bg-[#FADADD]', 'text-[#333333]', 'shadow-sm');
            btnCaterer.classList.remove('text-gray-500');
            btnCouple.classList.remove('bg-[#FADADD]', 'text-[#333333]', 'shadow-sm');
            btnCouple.classList.add('text-gray-500');

            // Affichage du bon formulaire
            catererForm.classList.remove('hidden');
            coupleForm.classList.add('hidden');

            // Mise à jour du rôle (2 = traiteur)
            roleIdInput.value = '2';
        });

        // Initialisation en fonction de la valeur actuelle du rôle
        const currentRole = roleIdInput.value;
        if (currentRole === '2') {
            btnCaterer.click();
        } else {
            btnCouple.click();
        }
    });
</script>
@endsection
