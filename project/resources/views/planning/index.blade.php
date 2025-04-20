@extends('layouts.app')

@section('title', 'Planning de mariage')

@section('styles')
<style>
    /* Style pour les cercles de traiteurs */
    .traiteur-circle {
        position: relative;
        width: 160px;
        height: 160px;
        border-radius: 50%;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        margin: 0 auto;
    }

    .traiteur-circle:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .traiteur-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .traiteur-circle .overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.7));
        padding: 12px 10px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .traiteur-circle:hover .overlay {
        background: linear-gradient(transparent, rgba(192, 128, 129, 0.9));
        opacity: 0;
    }

    .traiteur-circle .name {
        color: white;
        font-weight: 600;
        margin-bottom: 3px;
        font-size: 0.95rem;
    }

    .traiteur-circle .city {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.8rem;
    }

    .traiteur-circle .actions {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 8px;
        opacity: 0;
        background: rgba(255, 255, 255, 0.9);
        transition: all 0.3s ease;
        padding: 15px;
    }

    .traiteur-circle:hover .actions {
        opacity: 1;
    }

    .traiteur-circle .actions button {
        width: 90%;
        padding: 6px 12px;
        font-size: 0.85rem;
    }

    .badge-recommended {
        position: absolute;
        top: 5px;
        right: 5px;
        z-index: 10;
        background-color: #FADADD;
        color: #333;
        font-weight: 600;
        font-size: 0.65rem;
        padding: 4px 8px;
        border-radius: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    /* Style pour les étapes du processus */
    .process-step {
        position: relative;
        background: #f8f8f8;
        border-radius: 12px;
        padding: 25px;
        transition: all 0.3s ease;
        height: 100%;
    }

    .process-step:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    }

    .process-step .step-number {
        width: 40px;
        height: 40px;
        background: #FADADD;
        color: #333;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .process-step.completed .step-number {
        background: #C08081;
        color: white;
    }

    .process-step h3 {
        font-size: 1.2rem;
        margin-bottom: 10px;
        color: #333;
    }

    .process-step p {
        color: #666;
        font-size: 0.95rem;
    }

    .process-connector {
        position: absolute;
        top: 45px;
        right: -25px;
        width: 50px;
        height: 2px;
        background: #e0e0e0;
        z-index: 1;
    }

    /* Style pour le modal de sélection de date */
    .year-btn.selected, .month-btn.selected, .day-btn.selected {
        background-color: #C08081;
        color: white;
        border-color: #C08081;
    }

    .day-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background-color: #f3f4f6;
    }

    /* Animation pour la barre de progression */
    #progressBar {
        transition: width 0.3s ease;
    }

    /* Style pour les boutons des mois */
    .month-btn {
        font-size: 0.95rem;
        text-align: center;
    }

    /* Style pour la grille des jours */
    #daysGrid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
    }

    /* Style pour les jours du calendrier */
    .day-btn {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    @media (max-width: 768px) {
        .process-connector {
            display: none;
        }

        .traiteur-circle {
            width: 130px;
            height: 130px;
        }

        .traiteur-circle .actions button {
            font-size: 0.75rem;
        }
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Titre et introduction -->
            <div class="text-center mb-16">
                <h1 class="text-4xl font-bold text-[#333333] mb-4 font-display">Planning de Mariage</h1>
                <p class="text-gray-600 text-lg max-w-3xl mx-auto">
                    Trouvez le traiteur parfait pour votre grand jour ! Consultez les profils, vérifiez les disponibilités, et réservez directement en ligne.
                </p>
            </div>


             <!-- Traiteurs recommandés (mêmes villes que la mariée) -->
             @if($recommendedTraiteurs->isNotEmpty())
             <div class="mb-20">
                 <h2 class="text-2xl font-bold text-[#333333] mb-8 font-display">Traiteurs recommandés dans votre région</h2>

                 <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                     @foreach($recommendedTraiteurs as $traiteur)
                         <div class="relative group">
                             <!-- Badge "Recommandé" -->
                             <span class="badge-recommended">Recommandé</span>

                             <!-- Cercle du traiteur -->
                             <div class="traiteur-circle">
                                 @if($traiteur->logo)
                                     <img src="{{ asset('storage/' . $traiteur->logo) }}" alt="{{ $traiteur->manager_name }}">
                                 @else
                                     <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                         <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                         </svg>
                                     </div>
                                 @endif

                                 <!-- Informations de base -->
                                 <div class="overlay">
                                     <div class="name">{{ $traiteur->manager_name }}</div>
                                     <div class="city">{{ $traiteur->city }}</div>
                                 </div>

                                 <!-- Actions au survol -->
                                 <div class="actions">
                                     <button
                                         class="info-btn w-full px-3 py-1 bg-white text-gray-700 rounded-full border border-gray-300 flex items-center justify-center text-xs hover:bg-gray-100 transition"
                                         data-traiteur-id="{{ $traiteur->id }}"
                                     >
                                         <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                         </svg>
                                         Infos
                                     </button>

                                     <button
                                         class="book-btn w-full px-3 py-1 bg-[#FADADD] text-[#333333] rounded-full flex items-center justify-center text-xs hover:bg-[#C08081] hover:text-white transition"
                                         data-traiteur-id="{{ $traiteur->id }}"
                                     >
                                         <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                         </svg>
                                         Réserver
                                     </button>
                                 </div>
                             </div>
                         </div>
                     @endforeach
                 </div>
             </div>
         @endif

         <!-- Tous les traiteurs -->
         <div class="mb-20">
             <h2 class="text-2xl font-bold text-[#333333] mb-8 font-display">Tous les traiteurs disponibles</h2>

             <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                 @forelse($traiteurs as $traiteur)
                     <div class="relative group">
                         <!-- Cercle du traiteur -->
                         <div class="traiteur-circle">
                             @if($traiteur->logo)
                                 <img src="{{ asset('storage/' . $traiteur->logo) }}" alt="{{ $traiteur->manager_name }}">
                             @else
                                 <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                     <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                     </svg>
                                 </div>
                             @endif

                             <!-- Informations de base -->
                             <div class="overlay">
                                 <div class="name">{{ $traiteur->manager_name }}</div>
                                 <div class="city">{{ $traiteur->city }}</div>
                             </div>

                             <!-- Actions au survol -->
                             <div class="actions">
                                 <button
                                     class="info-btn w-full px-3 py-1 bg-white text-gray-700 rounded-full border border-gray-300 flex items-center justify-center text-xs hover:bg-gray-100 transition"
                                     data-traiteur-id="{{ $traiteur->id }}"
                                 >
                                     <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                     </svg>
                                     Infos
                                 </button>

                                 <button
                                     class="book-btn w-full px-3 py-1 bg-[#FADADD] text-[#333333] rounded-full flex items-center justify-center text-xs hover:bg-[#C08081] hover:text-white transition"
                                     data-traiteur-id="{{ $traiteur->id }}"
                                 >
                                     <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                     </svg>
                                     Réserver
                                 </button>
                             </div>
                         </div>
                     </div>
                 @empty
                     <div class="col-span-full text-center">
                         <p class="text-gray-600">Aucun traiteur n'est disponible pour le moment.</p>
                     </div>
                 @endforelse
             </div>
         </div>

            <!-- Processus de planification -->
            <div class="mb-20">
                <h2 class="text-2xl font-bold text-[#333333] mb-8 font-display text-center">Comment ça marche</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Étape 1 -->
                    <div class="process-step">
                        <div class="step-number">1</div>
                        <h3 class="font-display">Choisir un traiteur</h3>
                        <p>Parcourez notre sélection de traiteurs professionnels et trouvez celui qui correspond à vos attentes.</p>
                        <div class="process-connector hidden lg:block"></div>
                    </div>

                    <!-- Étape 2 -->
                    <div class="process-step">
                        <div class="step-number">2</div>
                        <h3 class="font-display">Réserver une date</h3>
                        <p>Vérifiez les disponibilités et réservez la date parfaite pour votre célébration.</p>
                        <div class="process-connector hidden lg:block"></div>
                    </div>

                    <!-- Étape 3 -->
                    <div class="process-step">
                        <div class="step-number">3</div>
                        <h3 class="font-display">Choisir vos services</h3>
                        <p>Sélectionnez les services qui rendront votre mariage unique et mémorable.</p>
                        <div class="process-connector hidden lg:block"></div>
                    </div>

                    <!-- Étape 4 -->
                    <div class="process-step">
                        <div class="step-number">4</div>
                        <h3 class="font-display">Félicitations !</h3>
                        <p>Tout est réservé ! Préparez-vous à vivre un moment inoubliable.</p>
                    </div>
                </div>
            </div>

            <!-- Citation inspirante -->
            <div class="bg-white rounded-xl shadow-sm p-8 mb-20 text-center">
                <div class="max-w-3xl mx-auto">
                    <svg class="w-10 h-10 text-[#FADADD] mx-auto mb-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                    </svg>
                    <p class="text-gray-700 text-xl italic font-light mb-4">Le mariage est non seulement l'union de deux personnes, mais aussi la fusion de deux familles, de deux histoires, pour en créer une nouvelle ensemble.</p>
                    <p class="text-[#C08081] font-medium">— Proverbe marocain</p>
                </div>
            </div>



            <!-- Section finale avec image et conseils -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-12">
                <div class="md:flex">
                    <div class="md:w-1/2">
                        <img src="{{ asset('images/wedding-planning.jpg') }}" alt="Planification de mariage" class="w-full h-full object-cover">
                    </div>
                    <div class="md:w-1/2 p-8 md:p-12 flex items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-[#333333] mb-4 font-display">Des conseils pour un mariage réussi</h2>
                            <p class="text-gray-600 mb-6">
                                Votre mariage est une journée unique qui mérite une organisation parfaite. Voici quelques conseils pour vous aider :
                            </p>
                            <ul class="space-y-4">
                                <li class="flex items-start">
                                    <svg class="h-6 w-6 text-[#C08081] mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700">Réservez vos prestataires au moins <strong>6 à 12 mois</strong> à l'avance.</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="h-6 w-6 text-[#C08081] mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700">Établissez un <strong>budget clair</strong> et suivez-le attentivement.</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="h-6 w-6 text-[#C08081] mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700">N'hésitez pas à <strong>goûter les menus</strong> avant de faire votre choix définitif.</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="h-6 w-6 text-[#C08081] mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700">Prévoyez un <strong>plan B</strong> pour les imprévus, surtout pour les mariages en extérieur.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour les informations du traiteur -->
<div id="infoModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl w-full max-w-md p-6 relative">
        <!-- Bouton fermer -->
        <button id="closeInfoModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Contenu du modal (sera rempli par JavaScript) -->
        <div id="infoModalContent" class="mt-4">
            <div class="flex justify-center mb-4">
                <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center" id="traiteurLogo">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>

            <h3 id="traiteurName" class="text-xl font-semibold text-[#333333] text-center mb-4">Nom du traiteur</h3>

            <div class="space-y-3">
                <p class="flex items-center text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#C08081]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span id="traiteurCity">Ville</span>
                </p>

                <p class="flex items-center text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#C08081]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span id="traiteurEmail">email@exemple.com</span>
                </p>

                <p class="flex items-center text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#C08081]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <span id="traiteurPhone">+212 6 12 34 56 78</span>
                </p>
            </div>

            <div class="mt-6">
                <button
                    id="infoModalBookBtn"
                    class="w-full bg-[#FADADD] hover:bg-[#C08081] text-[#333333] hover:text-white font-medium py-3 px-4 rounded-lg transition duration-300"
                >
                    Réserver ce traiteur
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour le calendrier de réservation (version plus grande) -->
<div id="bookModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl w-full max-w-2xl p-8 relative">
        <!-- Bouton fermer -->
        <button id="closeBookModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Barre de progression -->
        <div class="mb-6">
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div id="progressBar" class="bg-[#FADADD] h-2.5 rounded-full" style="width: 33%"></div>
            </div>
            <div class="flex justify-between text-xs text-gray-500 mt-1">
                <span>Année</span>
                <span>Mois</span>
                <span>Jour</span>
            </div>
        </div>

        <!-- Étape 1: Sélection de l'année -->
        <div id="yearSelectionStep" class="step-content">
            <h3 class="text-2xl font-semibold text-[#333333] mb-8 text-center">Quelle année pour votre mariage ?</h3>

            <div class="grid grid-cols-2 gap-6 mb-6">
                <!-- Les années seront générées dynamiquement ici -->
            </div>
        </div>

        <!-- Étape 2: Sélection du mois -->
        <div id="monthSelectionStep" class="step-content hidden">
            <h3 class="text-2xl font-semibold text-[#333333] mb-8 text-center">Quel mois pour votre mariage ?</h3>

            <div class="grid grid-cols-3 gap-6 mb-6">
                <button class="month-btn p-5 border rounded-lg text-lg hover:bg-[#FADADD] hover:border-[#C08081] transition-colors" data-month="1">
                    Janvier
                </button>
                <button class="month-btn p-5 border rounded-lg text-lg hover:bg-[#FADADD] hover:border-[#C08081] transition-colors" data-month="2">
                    Février
                </button>
                <button class="month-btn p-5 border rounded-lg text-lg hover:bg-[#FADADD] hover:border-[#C08081] transition-colors" data-month="3">
                    Mars
                </button>
                <button class="month-btn p-5 border rounded-lg text-lg hover:bg-[#FADADD] hover:border-[#C08081] transition-colors" data-month="4">
                    Avril
                </button>
                <button class="month-btn p-5 border rounded-lg text-lg hover:bg-[#FADADD] hover:border-[#C08081] transition-colors" data-month="5">
                    Mai
                </button>
                <button class="month-btn p-5 border rounded-lg text-lg hover:bg-[#FADADD] hover:border-[#C08081] transition-colors" data-month="6">
                    Juin
                </button>
                <button class="month-btn p-5 border rounded-lg text-lg hover:bg-[#FADADD] hover:border-[#C08081] transition-colors" data-month="7">
                    Juillet
                </button>
                <button class="month-btn p-5 border rounded-lg text-lg hover:bg-[#FADADD] hover:border-[#C08081] transition-colors" data-month="8">
                    Août
                </button>
                <button class="month-btn p-5 border rounded-lg text-lg hover:bg-[#FADADD] hover:border-[#C08081] transition-colors" data-month="9">
                    Septembre
                </button>
                <button class="month-btn p-5 border rounded-lg text-lg hover:bg-[#FADADD] hover:border-[#C08081] transition-colors" data-month="10">
                    Octobre
                </button>
                <button class="month-btn p-5 border rounded-lg text-lg hover:bg-[#FADADD] hover:border-[#C08081] transition-colors" data-month="11">
                    Novembre
                </button>
                <button class="month-btn p-5 border rounded-lg text-lg hover:bg-[#FADADD] hover:border-[#C08081] transition-colors" data-month="12">
                    Décembre
                </button>
            </div>
        </div>

        <!-- Étape 3: Sélection du jour -->
        <div id="daySelectionStep" class="step-content hidden">
            <h3 class="text-2xl font-semibold text-[#333333] mb-8 text-center">Quel jour pour votre mariage ?</h3>

            <div id="daysGrid" class="grid grid-cols-7 gap-3 mb-8">
                <!-- Les jours seront générés dynamiquement ici -->
            </div>
        </div>

        <!-- Message de disponibilité -->
        <div id="dateAvailabilityMessage" class="text-center mb-6 hidden">
            <p id="availabilityText" class="font-semibold text-lg"></p>
        </div>

        <!-- Boutons de navigation -->
        <div class="flex justify-between">
            <button
                id="backStepBtn"
                class="hidden px-6 py-3 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors text-lg"
            >
                Retour
            </button>

            <button
                id="confirmDateBtn"
                class="hidden ml-auto bg-[#FADADD] hover:bg-[#C08081] text-[#333333] hover:text-white font-medium py-3 px-6 rounded-lg transition duration-300 text-lg"
            >
                Continuer
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Éléments du DOM pour les modals
    const infoModal = document.getElementById('infoModal');
    const closeInfoModal = document.getElementById('closeInfoModal');
    const infoButtons = document.querySelectorAll('.info-btn');
    const bookButtons = document.querySelectorAll('.book-btn');
    const infoModalBookBtn = document.getElementById('infoModalBookBtn');

    // Éléments du DOM pour le modal de réservation
    const bookModal = document.getElementById('bookModal');
    const closeBookModal = document.getElementById('closeBookModal');
    const progressBar = document.getElementById('progressBar');
    const yearSelectionStep = document.getElementById('yearSelectionStep');
    const monthSelectionStep = document.getElementById('monthSelectionStep');
    const daySelectionStep = document.getElementById('daySelectionStep');
    const backStepBtn = document.getElementById('backStepBtn');
    const confirmDateBtn = document.getElementById('confirmDateBtn');
    const dateAvailabilityMessage = document.getElementById('dateAvailabilityMessage');
    const availabilityText = document.getElementById('availabilityText');
    const yearBtnsContainer = yearSelectionStep.querySelector('.grid');
    const daysGrid = document.getElementById('daysGrid');

    let currentTraiteurId = null;
    let selectedYear = null;
    let selectedMonth = null;
    let selectedDay = null;
    let currentStep = 1;
    let availableDates = [];

    // Génération des années (année en cours + 3 ans)
    function generateYearButtons() {
        yearBtnsContainer.innerHTML = '';
        const currentYear = new Date().getFullYear();

        for (let i = 0; i < 4; i++) {
            const year = currentYear + i;
            const btn = document.createElement('button');
            btn.classList.add('year-btn', 'p-5', 'border', 'rounded-lg', 'text-lg', 'hover:bg-[#FADADD]', 'hover:border-[#C08081]', 'transition-colors');
            btn.textContent = year;
            btn.dataset.year = year;

            btn.addEventListener('click', function() {
                // Supprimer la sélection précédente
                document.querySelectorAll('.year-btn.selected').forEach(el => {
                    el.classList.remove('selected');
                });

                // Sélectionner cette année
                this.classList.add('selected');
                selectedYear = parseInt(year);
                goToStep(2);
            });

            yearBtnsContainer.appendChild(btn);
        }
    }

    // Gestion des clics sur les mois
    const monthButtons = document.querySelectorAll('.month-btn');
    monthButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Supprimer la sélection précédente
            document.querySelectorAll('.month-btn.selected').forEach(el => {
                el.classList.remove('selected');
            });

            // Sélectionner ce mois
            this.classList.add('selected');
            selectedMonth = parseInt(this.dataset.month);

            generateDaysForMonth();
            goToStep(3);
        });
    });

    // Génération du calendrier pour le mois sélectionné
    function generateDaysForMonth() {
        daysGrid.innerHTML = '';

        // En-têtes des jours de la semaine
        const daysOfWeek = ['L', 'M', 'M', 'J', 'V', 'S', 'D'];
        daysOfWeek.forEach(day => {
            const dayHeader = document.createElement('div');
            dayHeader.classList.add('text-center', 'font-medium', 'text-gray-500', 'mb-2');
            dayHeader.textContent = day;
            daysGrid.appendChild(dayHeader);
        });

        // Déterminer le nombre de jours dans le mois
        const daysInMonth = new Date(selectedYear, selectedMonth, 0).getDate();

        // Déterminer le jour de la semaine du premier jour du mois (0 = dimanche, 1 = lundi, etc.)
        const firstDay = new Date(selectedYear, selectedMonth - 1, 1).getDay();
        const adjustedFirstDay = firstDay === 0 ? 6 : firstDay - 1; // Ajuster pour commencer par lundi

        // Ajouter des cellules vides pour les jours avant le début du mois
        for (let i = 0; i < adjustedFirstDay; i++) {
            const emptyDay = document.createElement('div');
            daysGrid.appendChild(emptyDay);
        }

        // Ajouter les jours du mois
        for (let i = 1; i <= daysInMonth; i++) {
            const dayBtn = document.createElement('button');
            dayBtn.classList.add('day-btn', 'h-10', 'w-10', 'rounded-full', 'flex', 'items-center', 'justify-center', 'hover:bg-[#FADADD]', 'transition-colors');
            dayBtn.textContent = i;
            dayBtn.dataset.day = i;

            // Vérifier si cette date est disponible
            const dateStr = `${selectedYear}-${String(selectedMonth).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
            const isAvailable = checkDateAvailability(dateStr);

            if (!isAvailable) {
                dayBtn.classList.add('opacity-50', 'cursor-not-allowed');
                dayBtn.disabled = true;
            } else {
                dayBtn.addEventListener('click', function() {
                    // Supprimer la sélection précédente
                    document.querySelectorAll('.day-btn.selected').forEach(el => {
                        el.classList.remove('selected');
                    });

                    // Marquer ce jour comme sélectionné
                    this.classList.add('selected');
                    selectedDay = i;

                    // Afficher le message de confirmation
                    dateAvailabilityMessage.classList.remove('hidden');
                    availabilityText.textContent = "Cette date est disponible !";
                    availabilityText.className = "font-semibold text-green-600 text-lg";

                    // Afficher le bouton de confirmation
                    confirmDateBtn.classList.remove('hidden');
                });
            }

            daysGrid.appendChild(dayBtn);
        }
    }

    // Vérifier si une date est disponible (version simplifiée)
    function checkDateAvailability(dateStr) {
        // Uniquement vérifier que la date est dans le futur
        const currentDate = new Date();
        const checkDate = new Date(dateStr);

        return checkDate >= currentDate;
    }

    // Navigation entre les étapes
    function goToStep(step) {
        currentStep = step;

        // Masquer toutes les étapes
        yearSelectionStep.classList.add('hidden');
        monthSelectionStep.classList.add('hidden');
        daySelectionStep.classList.add('hidden');

        // Afficher l'étape appropriée
        if (step === 1) {
            yearSelectionStep.classList.remove('hidden');
            backStepBtn.classList.add('hidden');
            progressBar.style.width = '33%';
        } else if (step === 2) {
            monthSelectionStep.classList.remove('hidden');
            backStepBtn.classList.remove('hidden');
            progressBar.style.width = '66%';
        } else if (step === 3) {
            daySelectionStep.classList.remove('hidden');
            backStepBtn.classList.remove('hidden');
            progressBar.style.width = '100%';
        }

        // Masquer le bouton de confirmation et le message lors du changement d'étape
        confirmDateBtn.classList.add('hidden');
        dateAvailabilityMessage.classList.add('hidden');
    }

    // Fonction pour ouvrir le modal d'information du traiteur
    function openInfoModal(traiteurId) {
        currentTraiteurId = traiteurId;

        // Récupérer les détails du traiteur via AJAX
        fetch(`/planning/traiteur/${traiteurId}`)
            .then(response => response.json())
            .then(data => {
                // Remplir le modal avec les détails
                document.getElementById('traiteurName').textContent = data.traiteur.manager_name;
                document.getElementById('traiteurCity').textContent = data.traiteur.city;
                document.getElementById('traiteurEmail').textContent = data.traiteur.user.email;
                document.getElementById('traiteurPhone').textContent = data.traiteur.phone_number;

                // Afficher le logo si disponible
                const logoContainer = document.getElementById('traiteurLogo');
                if (data.traiteur.logo) {
                    logoContainer.innerHTML = `<img src="/storage/${data.traiteur.logo}" alt="${data.traiteur.manager_name}" class="w-24 h-24 rounded-full object-cover">`;
                } else {
                    logoContainer.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    `;
                }

                infoModal.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des détails du traiteur:', error);
            });
    }

    // Fonction pour ouvrir le modal de réservation
    function openBookModal(traiteurId) {
        currentTraiteurId = traiteurId;

        // Récupérer les dates disponibles via AJAX (optionnel)
        fetch(`/planning/traiteur/${traiteurId}/available-dates`)
            .then(response => response.json())
            .then(data => {
                availableDates = data.availableDates;

                // Réinitialiser et afficher le modal
                resetModalState();
                generateYearButtons();
                goToStep(1);
                bookModal.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des dates disponibles:', error);

                // En cas d'erreur, on continue quand même mais sans filtrer les dates
                resetModalState();
                generateYearButtons();
                goToStep(1);
                bookModal.classList.remove('hidden');
            });
    }

    // Réinitialiser l'état du modal
    function resetModalState() {
        selectedYear = null;
        selectedMonth = null;
        selectedDay = null;
        currentStep = 1;

        // Réinitialiser les sélections
        document.querySelectorAll('.year-btn.selected, .month-btn.selected, .day-btn.selected').forEach(el => {
            el.classList.remove('selected');
        });

        confirmDateBtn.classList.add('hidden');
        dateAvailabilityMessage.classList.add('hidden');
    }

    // Gérer le clic sur le bouton Infos
    infoButtons.forEach(button => {
        button.addEventListener('click', function() {
            const traiteurId = this.getAttribute('data-traiteur-id');
            openInfoModal(traiteurId);
        });
    });

    // Gérer le clic sur le bouton Réserver
    bookButtons.forEach(button => {
        button.addEventListener('click', function() {
            const traiteurId = this.getAttribute('data-traiteur-id');
            openBookModal(traiteurId);
        });
    });

    // Gérer le clic sur le bouton Réserver dans le modal d'information
    infoModalBookBtn.addEventListener('click', function() {
        infoModal.classList.add('hidden');
        openBookModal(currentTraiteurId);
    });

    // Gérer le clic sur le bouton Retour
    backStepBtn.addEventListener('click', function() {
        if (currentStep > 1) {
            goToStep(currentStep - 1);
        }
    });


    confirmDateBtn.addEventListener('click', function() {
        if (selectedYear && selectedMonth && selectedDay) {
            const dateStr = `${selectedYear}-${String(selectedMonth).padStart(2, '0')}-${String(selectedDay).padStart(2, '0')}`;
            // Rediriger vers la page des services du traiteur avec la date sélectionnée
            window.location.href = `/traiteur/${currentTraiteurId}/services?date=${dateStr}`;
        }
    });


    closeInfoModal.addEventListener('click', function() {
        infoModal.classList.add('hidden');
    });


    closeBookModal.addEventListener('click', function() {
        bookModal.classList.add('hidden');
    });

});
</script>
@endsection
