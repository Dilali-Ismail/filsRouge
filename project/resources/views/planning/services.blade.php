@extends('layouts.app')

@section('title', 'Services du traiteur ' . $traiteur->manager_name)

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <!-- En-tête -->
            <div class="mb-8">
                <div class="flex items-center">
                    <a href="{{ route('planning.index') }}" class="mr-4 text-gray-600 hover:text-[#C08081]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <h1 class="text-3xl font-bold text-[#333333] font-display">Services de {{ $traiteur->manager_name }}</h1>
                </div>
                <p class="text-gray-600 mt-2">Découvrez les services proposés par ce traiteur.</p>
            </div>

            <!-- Informations sur le traiteur -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <div class="flex flex-col md:flex-row items-start">
                    <div class="w-full md:w-1/4 mb-4 md:mb-0">
                        @if($traiteur->logo)
                            <img src="{{ asset('storage/' . $traiteur->logo) }}" alt="{{ $traiteur->manager_name }}" class="w-full h-48 object-cover rounded-lg">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="w-full md:w-3/4 md:pl-6">
                        <h2 class="text-xl font-semibold text-[#333333] mb-2">{{ $traiteur->manager_name }}</h2>
                        <div class="flex items-center text-gray-600 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $traiteur->city }}
                        </div>
                        <div class="flex items-center text-gray-600 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            {{ $traiteur->phone_number }}
                        </div>

                        <!-- Bouton pour réserver -->
                        <form action="{{ route('reservations.create') }}" method="GET" class="mt-4">
                            <input type="hidden" name="traiteur_id" value="{{ $traiteur->id }}">
                            <input type="hidden" name="event_date" value="{{ request('date') }}">
                            <button type="submit" class="px-4 py-2 bg-[#FADADD] hover:bg-[#C08081] text-[#333333] hover:text-white rounded-lg transition duration-300 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Réserver pour le {{ \Carbon\Carbon::parse(request('date'))->format('d/m/Y') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Liste des services par catégorie -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-semibold text-[#333333] mb-6">Services disponibles</h2>

                <!-- Onglets pour les catégories de services -->
                <div class="mb-8">
                    <ul class="flex flex-wrap -mb-px border-b border-gray-200">
                        @foreach($categories as $category)
                            <li class="mr-2">
                                <a href="#{{ $category->name }}" class="inline-block p-4 rounded-t-lg border-b-2 {{ $loop->first ? 'border-[#C08081] text-[#C08081]' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}">
                                    {{ ucfirst($category->name) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Contenu des onglets -->
                <div>
                    @foreach($categories as $category)
                        <div id="{{ $category->name }}" class="service-tab {{ $loop->first ? '' : 'hidden' }}">
                            @php
                                $services = $traiteur->services->where('category_id', $category->id);
                            @endphp

                            @if($services->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($services as $service)
                                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-all duration-300">
                                            <div class="p-5">
                                                <h3 class="text-lg font-semibold text-[#333333] mb-2">{{ $service->title }}</h3>
                                                <p class="text-gray-600 text-sm mb-4">{{ $service->description }}</p>

                                                <!-- Affichage des éléments selon la catégorie -->
                                                @if($category->name == 'menu' && $service->menuItems->count() > 0)
                                                    <div class="mt-3">
                                                        <p class="text-sm font-medium text-gray-500 mb-2">Exemples de plats :</p>
                                                        <ul class="text-sm text-gray-600">
                                                            @foreach($service->menuItems->take(3) as $item)
                                                                <li class="mb-1 flex justify-between">
                                                                    <span>{{ $item->name }}</span>
                                                                    <span class="font-medium text-[#C08081]">{{ number_format($item->price, 2) }} DH</span>
                                                                </li>
                                                            @endforeach
                                                            @if($service->menuItems->count() > 3)
                                                                <li class="text-sm text-gray-500 italic">Et {{ $service->menuItems->count() - 3 }} autres plats...</li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                @elseif($category->name == 'decoration' && $service->decorations->count() > 0)
                                                    <div class="mt-3">
                                                        <p class="text-sm font-medium text-gray-500 mb-2">Thèmes de décoration :</p>
                                                        <ul class="text-sm text-gray-600">
                                                            @foreach($service->decorations->take(3) as $item)
                                                                <li class="mb-1 flex justify-between">
                                                                    <span>{{ $item->name }}</span>
                                                                    <span class="font-medium text-[#C08081]">{{ number_format($item->price, 2) }} DH</span>
                                                                </li>
                                                            @endforeach
                                                            @if($service->decorations->count() > 3)
                                                                <li class="text-sm text-gray-500 italic">Et {{ $service->decorations->count() - 3 }} autres thèmes...</li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                @elseif($category->name == 'salles' && $service->salles->count() > 0)
                                                    <div class="mt-3">
                                                        <p class="text-sm font-medium text-gray-500 mb-2">Salles disponibles :</p>
                                                        <ul class="text-sm text-gray-600">
                                                            @foreach($service->salles->take(3) as $item)
                                                                <li class="mb-1 flex justify-between">
                                                                    <span>{{ $item->name }} ({{ $item->tables_count }} tables)</span>
                                                                    <span class="font-medium text-[#C08081]">{{ number_format($item->price, 2) }} DH</span>
                                                                </li>
                                                            @endforeach
                                                            @if($service->salles->count() > 3)
                                                                <li class="text-sm text-gray-500 italic">Et {{ $service->salles->count() - 3 }} autres salles...</li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                @endif

                                                <a href="#" class="mt-4 inline-flex items-center text-sm font-medium text-[#C08081] hover:underline">
                                                    Voir tous les détails
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-600 mb-2">Aucun service disponible</h3>
                                    <p class="text-gray-500">Ce traiteur ne propose pas de services dans cette catégorie pour le moment.</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion des onglets
        const tabLinks = document.querySelectorAll('ul li a');
        const tabContents = document.querySelectorAll('.service-tab');

        tabLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                // Mise à jour des onglets actifs
                tabLinks.forEach(l => {
                    l.classList.remove('border-[#C08081]', 'text-[#C08081]');
                    l.classList.add('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
                });

                this.classList.remove('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
                this.classList.add('border-[#C08081]', 'text-[#C08081]');

                // Affichage du contenu correspondant
                const target = this.getAttribute('href').substring(1);
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });
                document.getElementById(target).classList.remove('hidden');
            });
        });
    });
</script>
@endsection
