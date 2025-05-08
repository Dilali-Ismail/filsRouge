@extends('layouts.app')

@section('title', 'Accueil')

@section('styles')
<style>
    /* Styles pour la section héro */
    .hero-section {
        position: relative;
        width: 100%;
        background-color: #ecd4d8;
        margin-top: -1px; /* Supprime tout espace entre le header et la section héro */
        padding: 0;
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to right, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.3) 100%);
    }

    /* Styles pour la section "Comment ça marche" */
    .how-it-works {
        background-color: #fff;
        position: relative;
        overflow: hidden;
    }

    .step-container {
        position: relative;
        z-index: 2;
        transition: transform 0.3s ease;
    }

    .step-container:hover {
        transform: translateY(-10px);
    }

    .step-number {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        margin: 0 auto 1.5rem;
        background-color: #FADADD;
        color: white;
        position: relative;
        z-index: 1;
        box-shadow: 0 8px 16px rgba(192, 128, 129, 0.2);
        border: 4px solid white;
    }

    .step-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 10px 10px 0 0;
        position: relative;
    }

    .step-image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0,0,0,0) 70%, rgba(0,0,0,0.4) 100%);
        border-radius: 10px 10px 0 0;
    }

    .step-content {
        background-color: white;
        padding: 2rem;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        border: 1px solid #f0f0f0;
        border-top: none;
    }

    .decorative-element {
        position: absolute;
        opacity: 0.08;
        z-index: 0;
    }

    .decorative-left {
        top: 15%;
        left: -50px;
        width: 180px;
    }

    .decorative-right {
        bottom: 15%;
        right: -50px;
        width: 180px;
        transform: rotate(180deg);
    }

    .section-title {
        position: relative;
        display: inline-block;
    }

    .section-title::after {
        content: "";
        position: absolute;
        bottom: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background: linear-gradient(to right, #C08081, #FADADD);
    }

    .btn-gradient {
        background: linear-gradient(to right, #9c6baa, #C08081);
        transition: all 0.3s ease;
    }

    .btn-gradient:hover {
        background: linear-gradient(to right, #8a5996, #a56869);
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection

@section('content')
    <!-- Section Hero (bord à bord, sans espace) -->
    <section class="hero-section">
        <!-- Image de fond avec overlay -->
        <div class="relative overflow-hidden w-full" style="height: 85vh;">
            <img src="{{ asset('images/hero-section.webp') }}" alt="Mariage marocain" class="w-full h-full object-cover">
            <div class="hero-overlay"></div>

            <!-- Contenu du Hero -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="container mx-auto px-4">
                    <div class="max-w-3xl mx-auto text-center">
                        <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl font-display">
                            Bienvenue sur Alf Mabrouk
                        </h1>
                        <p class="mt-6 text-xl text-white font-light">
                            La plateforme qui simplifie l'organisation de votre mariage
                        </p>
                        <div class="mt-12 flex justify-center">
                            <div class="inline-flex rounded-md shadow">
                                <a href="{{ url('/planning') }}" class="inline-flex items-center justify-center px-6 py-4 border border-transparent text-base font-medium rounded-lg text-white btn-gradient shadow-md hover:shadow-lg transition-all duration-300">
                                    Commencer
                                </a>
                            </div>
                            <div class="ml-4 inline-flex rounded-md shadow">
                                <a href="{{ url('/services') }}" class="inline-flex items-center justify-center px-6 py-4 border border-transparent text-base font-medium rounded-lg text-[#C08081] bg-white hover:bg-gray-50 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                    Nos Services
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section "Comment ça marche" avec design moderne et élégant -->
    <section class="how-it-works py-24">
        <div class="container mx-auto px-4">
            <!-- Éléments décoratifs -->
            <img src="{{ asset('images/floral-design.png') }}" alt="" class="decorative-element decorative-left">
            <img src="{{ asset('images/floral-design.png') }}" alt="" class="decorative-element decorative-right">

            <div class="text-center mb-20">
                <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 section-title">Comment ça marche</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 max-w-6xl mx-auto">
                <!-- Étape 1 -->
                <div class="step-container">
                    <div class="relative">
                        <div class="step-image" style="background-image: url('{{ asset('images/step1.jpeg') }}')">
                            <div class="step-image-overlay"></div>
                        </div>
                        <div class="absolute -top-10 left-1/2 transform -translate-x-1/2">
                            <div class="step-number">1</div>
                        </div>
                    </div>
                    <div class="step-content">
                        <h3 class="text-xl font-bold text-center text-gray-900 mb-3">Créer un compte</h3>
                        <p class="text-gray-600 text-center">Inscrivez-vous en tant que futur marié pour accéder à toutes les fonctionnalités et commencer votre préparation.</p>
                    </div>
                </div>

                <!-- Étape 2 -->
                <div class="step-container">
                    <div class="relative">
                        <div class="step-image" style="background-image: url('{{ asset('images/step2.jpg') }}')">
                            <div class="step-image-overlay"></div>
                        </div>
                        <div class="absolute -top-10 left-1/2 transform -translate-x-1/2">
                            <div class="step-number">2</div>
                        </div>
                    </div>
                    <div class="step-content">
                        <h3 class="text-xl font-bold text-center text-gray-900 mb-3">Choisir un traiteur</h3>
                        <p class="text-gray-600 text-center">Parcourez notre liste de traiteurs partenaires et sélectionnez celui qui correspond à vos attentes et votre budget.</p>
                    </div>
                </div>

                <!-- Étape 3 -->
                <div class="step-container">
                    <div class="relative">
                        <div class="step-image" style="background-image: url('{{ asset('images/step3.jpg') }}')">
                            <div class="step-image-overlay"></div>
                        </div>
                        <div class="absolute -top-10 left-1/2 transform -translate-x-1/2">
                            <div class="step-number">3</div>
                        </div>
                    </div>
                    <div class="step-content">
                        <h3 class="text-xl font-bold text-center text-gray-900 mb-3">Planifiez et réservez</h3>
                        <p class="text-gray-600 text-center">Réservez vos services et gérez votre planning de mariage facilement en ligne. Suivez votre progression jusqu'au grand jour.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    // Vous pouvez ajouter ici des scripts spécifiques à la page d'accueil
</script>
@endsection
