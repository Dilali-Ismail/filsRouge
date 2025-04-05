@extends('layouts.app')

@section('title', 'À propos')

@section('content')
    <!-- En-tête de page À propos -->
    <div class="bg-[#FADADD]/10 py-16">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-bold font-display text-center text-[#333333]">À propos de nous</h1>
            <div class="w-24 h-1 bg-[#FADADD] mx-auto mt-4"></div>
        </div>
    </div>

    <!-- Contenu principal À propos -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center">
                <!-- Colonne de gauche : Texte -->
                <div class="md:w-1/2 md:pr-12 mb-8 md:mb-0">
                    <h2 class="text-3xl md:text-4xl font-display font-bold text-[#333333] mb-6">Votre mariage, notre engagement</h2>
                    <div class="w-20 h-1 bg-[#FADADD] mb-8"></div>

                    <p class="text-[#333333] mb-6">
                        Notre plateforme a été pensée pour accompagner
                        <span class="font-bold">nos mariées</span> dans l'organisation de leur mariage de manière simple, rapide et efficace.
                    </p>

                    <p class="text-[#333333] mb-6">
                        Nous collaborons avec des traiteurs, des prestataires et des professionnels de confiance à travers tout le Maroc,
                        afin de vous offrir des services personnalisés, adaptés à vos besoins et à vos traditions.
                    </p>

                    <p class="text-[#333333]">
                        Grâce à une interface intuitive et des outils de planification performants,
                        <span class="font-bold">nos mariées</span> peuvent organiser chaque détail de leur cérémonie,
                        en toute sérénité, tout en étant accompagnées par les meilleurs experts du domaine.
                    </p>
                </div>

                <!-- Colonne de droite : Image -->
                <div class="md:w-1/2">
                    <div class="relative">
                        <div class="absolute -inset-4 bg-[#FADADD]/20 rounded-lg transform rotate-3"></div>
                        <img src="{{ asset('images/moroccan-wedding.jpg') }}" alt="Mariage marocain" class="relative rounded-lg shadow-lg w-full h-auto object-cover">
                        <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-[#D4AF37]/20 rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Notre Mission -->
    <div class="bg-[#FAF9F6] py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold font-display text-[#333333] mb-4">Notre Mission</h2>
                <div class="w-20 h-1 bg-[#FADADD] mx-auto mb-8"></div>
                <p class="text-[#333333] max-w-3xl mx-auto">
                    Simplifier l'organisation des mariages au Maroc en mettant en relation les futurs mariés avec les meilleurs prestataires du pays.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <!-- Valeur 1 -->
                <div class="bg-white p-8 rounded-lg shadow-md text-center">
                    <div class="w-16 h-16 bg-[#FADADD]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#C08081]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#333333] mb-2">Qualité</h3>
                    <p class="text-[#333333]">Nous sélectionnons rigoureusement nos prestataires pour garantir des services de haute qualité.</p>
                </div>

                <!-- Valeur 2 -->
                <div class="bg-white p-8 rounded-lg shadow-md text-center">
                    <div class="w-16 h-16 bg-[#FADADD]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#C08081]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#333333] mb-2">Simplicité</h3>
                    <p class="text-[#333333]">Notre plateforme est conçue pour être intuitive et facile à utiliser, économisant temps et énergie.</p>
                </div>

                <!-- Valeur 3 -->
                <div class="bg-white p-8 rounded-lg shadow-md text-center">
                    <div class="w-16 h-16 bg-[#FADADD]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#C08081]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#333333] mb-2">Personnalisation</h3>
                    <p class="text-[#333333]">Nous respectons les traditions marocaines tout en offrant des options adaptées à vos préférences.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
