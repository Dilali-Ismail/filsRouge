@extends('layouts.app')

@section('title', 'Sélection des services')

@section('styles')
<style>
    /* Style pour la barre latérale gauche */
    .sidebar-category {
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }

    .sidebar-category.active {
        background-color: #FADADD;
        border-left: 3px solid #C08081;
    }

    /* Style pour les cartes de service */
    .service-card {
        transition: all 0.3s ease;
    }

    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    /* Style pour les checkbox personnalisées */
    .custom-checkbox {
        display: flex;
        align-items: center;
    }

    .custom-checkbox input[type="checkbox"] {
        appearance: none;
        -webkit-appearance: none;
        width: 22px;
        height: 22px;
        border: 2px solid #FADADD;
        border-radius: 4px;
        outline: none;
        cursor: pointer;
        position: relative;
        transition: all 0.2s ease;
    }

    .custom-checkbox input[type="checkbox"]:checked {
        background-color: #C08081;
        border-color: #C08081;
    }

    .custom-checkbox input[type="checkbox"]:checked::after {
        content: '';
        position: absolute;
        top: 4px;
        left: 7px;
        width: 6px;
        height: 10px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    /* Style pour le budget */
    .budget-card {
        position: sticky;
        top: 90px;
        transition: top 0.3s ease;
    }

    .budget-item {
        transition: all 0.3s ease;
    }

    /* Animation pour les éléments sélectionnés */
    .selected-animation {
        animation: pulse 1s;
    }

    @keyframes pulse {
        0% { opacity: 0.5; }
        50% { opacity: 1; }
        100% { opacity: 0.5; }
    }

    /* Styles d'onglets pour le menu */
    .tab-button {
        position: relative;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .tab-button::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 3px;
        background-color: #C08081;
        transition: all 0.3s ease;
        transform: translateX(-50%);
    }

    .tab-button.active {
        color: #C08081;
    }

    .tab-button.active::after {
        width: 80%;
    }

    /* Style pour la galerie */
    .portfolio-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px;
    }

    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        cursor: pointer;
    }

    .gallery-item img {
        transition: transform 0.3s ease;
        width: 100%;
        height: auto;
        object-fit: cover;
        aspect-ratio: 1/1;
    }

    .gallery-item:hover img {
        transform: scale(1.05);
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-7xl mx-auto">
            <!-- En-tête avec informations de réservation -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-[#333333] mb-2 font-display">Services de {{ $traiteur->manager_name }}</h1>
                        <p class="text-gray-600">
                            Date choisie: <span class="font-medium">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</span>
                        </p>
                    </div>

                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('planning.index') }}" class="inline-flex items-center text-gray-600 hover:text-[#C08081]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Modifier ma sélection
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Barre latérale gauche (catégories) -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="p-4 bg-gray-50 border-b">
                            <h2 class="font-semibold text-[#333333]">Catégories de services</h2>
                        </div>

                        <div class="divide-y">
                            @foreach($categories as $category)
                            <a
                                href="{{ route('planning.traiteur.services', ['traiteurId' => $traiteur->id, 'date' => $date, 'category' => $category->name]) }}"
                                class="sidebar-category block p-4 hover:bg-gray-50 {{ $activeCategory->id === $category->id ? 'active' : '' }}"
                            >
                                <div class="flex items-center">
                                    <!-- Icône selon la catégorie -->
                                    <div class="mr-3">
                                        @if($category->name === 'menu')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#C08081]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        @elseif($category->name === 'vetements')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#C08081]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                        @elseif($category->name === 'negafa')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#C08081]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        @elseif($category->name === 'photographer')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#C08081]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        @elseif($category->name === 'maquillage')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#C08081]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                            </svg>
                                        @elseif($category->name === 'salles')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#C08081]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                        @elseif($category->name === 'decoration')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#C08081]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                            </svg>
                                        @elseif($category->name === 'amariya')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#C08081]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                            </svg>
                                        @elseif($category->name === 'animation')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#C08081]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#C08081]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @endif
                                    </div>

                                    <span class="{{ $activeCategory->id === $category->id ? 'font-semibold text-[#333333]' : 'text-gray-600' }}">
                                        {{ ucfirst($category->name) }}
                                    </span>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Section centrale (contenu dynamique) -->
                <div class="lg:col-span-6">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-2xl font-bold text-[#333333] mb-6 font-display">{{ ucfirst($activeCategory->name) }}</h2>

                        @if($activeCategory->name === 'menu')
                            <!-- Affichage des menus -->
                            <div class="space-y-8">
                                @if($services->isEmpty())
                                    <p class="text-gray-500 text-center py-4">Aucun menu disponible.</p>
                                @else
                                    @foreach($services as $menu)
                                        <div class="service-card border rounded-lg overflow-hidden">
                                            <div class="p-5 bg-gray-50 border-b">
                                                <div class="flex justify-between items-center">
                                                    <h3 class="text-xl font-semibold text-[#333333]">{{ $menu->title }}</h3>
                                                    <div class="custom-checkbox">
                                                        <input
                                                            type="checkbox"
                                                            id="menu-{{ $menu->id }}"
                                                            class="service-checkbox"
                                                            data-type="menu"
                                                            data-id="{{ $menu->id }}"
                                                            data-name="{{ $menu->title }}"
                                                            data-price="{{ $menu->menuItems->sum('price') }}"
                                                        >
                                                    </div>
                                                </div>
                                                @if($menu->description)
                                                    <p class="text-gray-600 mt-2">{{ $menu->description }}</p>
                                                @endif
                                            </div>

                                            <!-- Onglets pour les catégories d'items -->
                                            <div class="px-5 pt-4">
                                                <div class="flex border-b">
                                                    <button class="tab-button pb-2 px-4 mr-4 active" data-menu-id="{{ $menu->id }}" data-category="boisson">Boissons</button>
                                                    <button class="tab-button pb-2 px-4 mr-4" data-menu-id="{{ $menu->id }}" data-category="entree">Entrées</button>
                                                    <button class="tab-button pb-2 px-4 mr-4" data-menu-id="{{ $menu->id }}" data-category="plat">Plats</button>
                                                    <button class="tab-button pb-2 px-4" data-menu-id="{{ $menu->id }}" data-category="dessert">Desserts</button>
                                                </div>
                                            </div>

                                            <!-- Contenu des onglets -->
                                            <div class="p-5">
                                                @php
                                                    $categories = ['boisson', 'entree', 'plat', 'dessert'];
                                                @endphp

                                                @foreach($categories as $cat)
                                                    <div class="menu-items-{{ $menu->id }}-{{ $cat }} {{ $cat === 'boisson' ? '' : 'hidden' }}">
                                                        @php
                                                            $items = $menu->menuItems->where('category', $cat);
                                                        @endphp

                                                        @if($items->isEmpty())
                                                            <p class="text-gray-500 text-center py-4">Aucun item dans cette catégorie.</p>
                                                        @else
                                                            <div class="space-y-4">
                                                                @foreach($items as $item)
                                                                    <div class="flex justify-between items-start p-3 hover:bg-gray-50 rounded-lg transition">
                                                                        <div class="flex items-start">
                                                                            @if($item->photo)
                                                                                <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->name }}" class="w-16 h-16 object-cover rounded-lg mr-4">
                                                                            @endif
                                                                            <div>
                                                                                <h4 class="font-medium text-[#333333]">{{ $item->name }}</h4>
                                                                                @if($item->description)
                                                                                    <p class="text-sm text-gray-600 mt-1">{{ $item->description }}</p>
                                                                                @endif
                                                                                <p class="text-[#C08081] font-medium mt-1">{{ number_format($item->price, 2) }} MAD</p>
                                                                            </div>
                                                                        </div>

                                                                        <div class="custom-checkbox">
                                                                            <input
                                                                                type="checkbox"
                                                                                id="item-{{ $item->id }}"
                                                                                class="service-checkbox menu-item-checkbox"
                                                                                data-type="menu-item"
                                                                                data-id="{{ $item->id }}"
                                                                                data-name="{{ $item->name }}"
                                                                                data-price="{{ $item->price }}"
                                                                                data-parent-id="{{ $menu->id }}"
                                                                            >
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @elseif($activeCategory->name === 'vetements')
                            <!-- Affichage des vêtements -->
                            <div class="space-y-8">
                                <!-- Onglets pour les styles -->
                                <div class="flex border-b mb-6">
                                    <button class="tab-button pb-2 px-6 mr-6 active" data-style="traditionnel">Traditionnel</button>
                                    <button class="tab-button pb-2 px-6" data-style="moderne">Moderne</button>
                                </div>

                                <!-- Contenu des styles -->
                                <div class="style-content" id="traditionnel-content">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Robe de mariée traditionnelle -->
                                        <div>
                                            <h3 class="text-xl font-semibold text-[#333333] mb-4">Robe de mariée</h3>
                                            <div class="space-y-4">
                                                @php
                                                    $robes = $services->where('category_id', $activeCategory->id)
                                                                    ->where('style', 'traditionnel')
                                                                    ->where('category', 'robe_mariee');
                                                @endphp

                                                @if($robes->isEmpty())
                                                    <p class="text-gray-500 text-center py-4">Aucune robe disponible.</p>
                                                @else
                                                    @foreach($robes as $robe)
                                                        <div class="service-card border rounded-lg overflow-hidden">
                                                            @if($robe->photo)
                                                                <img src="{{ asset('storage/' . $robe->photo) }}" alt="{{ $robe->name }}" class="w-full h-48 object-cover">
                                                            @endif
                                                            <div class="p-4">
                                                                <div class="flex justify-between items-start">
                                                                    <div>
                                                                        <h4 class="font-medium text-[#333333]">{{ $robe->name }}</h4>
                                                                        @if($robe->description)
                                                                            <p class="text-sm text-gray-600 mt-1">{{ $robe->description }}</p>
                                                                        @endif
                                                                        <p class="text-[#C08081] font-medium mt-1">{{ number_format($robe->price, 2) }} MAD</p>
                                                                    </div>

                                                                    <div class="custom-checkbox">
                                                                        <input
                                                                            type="checkbox"
                                                                            id="vetement-{{ $robe->id }}"
                                                                            class="service-checkbox"
                                                                            data-type="vetement"
                                                                            data-id="{{ $robe->id }}"
                                                                            data-name="{{ $robe->name }}"
                                                                            data-price="{{ $robe->price }}"
                                                                        >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Costume homme traditionnel -->
                                        <div>
                                            <h3 class="text-xl font-semibold text-[#333333] mb-4">Costume homme</h3>
                                            <div class="space-y-4">
                                                @php
                                                    $costumes = $services->where('category_id', $activeCategory->id)
                                                                        ->where('style', 'traditionnel')
                                                                        ->where('category', 'costume_homme');
                                                @endphp

                                                @if($costumes->isEmpty())
                                                    <p class="text-gray-500 text-center py-4">Aucun costume disponible.</p>
                                                @else
                                                    @foreach($costumes as $costume)
                                                        <div class="service-card border rounded-lg overflow-hidden">
                                                            @if($costume->photo)
                                                                <img src="{{ asset('storage/' . $costume->photo) }}" alt="{{ $costume->name }}" class="w-full h-48 object-cover">
                                                            @endif
                                                            <div class="p-4">
                                                                <div class="flex justify-between items-start">
                                                                    <div>
                                                                        <h4 class="font-medium text-[#333333]">{{ $costume->name }}</h4>
                                                                        @if($costume->description)
                                                                            <p class="text-sm text-gray-600 mt-1">{{ $costume->description }}</p>
                                                                        @endif
                                                                        <p class="text-[#C08081] font-medium mt-1">{{ number_format($costume->price, 2) }} MAD</p>
                                                                    </div>

                                                                    <div class="custom-checkbox">
                                                                        <input
                                                                            type="checkbox"
                                                                            id="vetement-{{ $costume->id }}"
                                                                            class="service-checkbox"
                                                                            data-type="vetement"
                                                                            data-id="{{ $costume->id }}"
                                                                            data-name="{{ $costume->name }}"
                                                                            data-price="{{ $costume->price }}"
                                                                        >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="style-content hidden" id="moderne-content">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Robe de mariée moderne -->
                                        <div>
                                            <h3 class="text-xl font-semibold text-[#333333] mb-4">Robe de mariée</h3>
                                            <div class="space-y-4">
                                                @php
                                                    $robesModernes = $services->where('category_id', $activeCategory->id)
                                                                    ->where('style', 'moderne')
                                                                    ->where('category', 'robe_mariee');
                                                @endphp

                                                @if($robesModernes->isEmpty())
                                                    <p class="text-gray-500 text-center py-4">Aucune robe disponible.</p>
                                                @else
                                                    @foreach($robesModernes as $robe)
                                                        <div class="service-card border rounded-lg overflow-hidden">
                                                            @if($robe->photo)
                                                                <img src="{{ asset('storage/' . $robe->photo) }}" alt="{{ $robe->name }}" class="w-full h-48 object-cover">
                                                            @endif
                                                            <div class="p-4">
                                                                <div class="flex justify-between items-start">
                                                                    <div>
                                                                        <h4 class="font-medium text-[#333333]">{{ $robe->name }}</h4>
                                                                        @if($robe->description)
                                                                            <p class="text-sm text-gray-600 mt-1">{{ $robe->description }}</p>
                                                                        @endif
                                                                        <p class="text-[#C08081] font-medium mt-1">{{ number_format($robe->price, 2) }} MAD</p>
                                                                    </div>

                                                                    <div class="custom-checkbox">
                                                                        <input
                                                                            type="checkbox"
                                                                            id="vetement-{{ $robe->id }}"
                                                                            class="service-checkbox"
                                                                            data-type="vetement"
                                                                            data-id="{{ $robe->id }}"
                                                                            data-name="{{ $robe->name }}"
                                                                            data-price="{{ $robe->price }}"
                                                                        >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Costume homme moderne -->
                                        <div>
                                            <h3 class="text-xl font-semibold text-[#333333] mb-4">Costume homme</h3>
                                            <div class="space-y-4">
                                                @php
                                                    $costumesModernes = $services->where('category_id', $activeCategory->id)
                                                    ->where('style', 'moderne')
                                                                       ->where('category', 'costume_homme');
                                               @endphp

                                               @if($costumesModernes->isEmpty())
                                                   <p class="text-gray-500 text-center py-4">Aucun costume disponible.</p>
                                               @else
                                                   @foreach($costumesModernes as $costume)
                                                       <div class="service-card border rounded-lg overflow-hidden">
                                                           @if($costume->photo)
                                                               <img src="{{ asset('storage/' . $costume->photo) }}" alt="{{ $costume->name }}" class="w-full h-48 object-cover">
                                                           @endif
                                                           <div class="p-4">
                                                               <div class="flex justify-between items-start">
                                                                   <div>
                                                                       <h4 class="font-medium text-[#333333]">{{ $costume->name }}</h4>
                                                                       @if($costume->description)
                                                                           <p class="text-sm text-gray-600 mt-1">{{ $costume->description }}</p>
                                                                       @endif
                                                                       <p class="text-[#C08081] font-medium mt-1">{{ number_format($costume->price, 2) }} MAD</p>
                                                                   </div>

                                                                   <div class="custom-checkbox">
                                                                       <input
                                                                           type="checkbox"
                                                                           id="vetement-{{ $costume->id }}"
                                                                           class="service-checkbox"
                                                                           data-type="vetement"
                                                                           data-id="{{ $costume->id }}"
                                                                           data-name="{{ $costume->name }}"
                                                                           data-price="{{ $costume->price }}"
                                                                       >
                                                                   </div>
                                                               </div>
                                                           </div>
                                                       </div>
                                                   @endforeach
                                               @endif
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       @elseif(in_array($activeCategory->name, ['negafa', 'maquillage', 'photographer']))
                           <!-- Affichage pour Negafa, Maquilleuse et Photographe (avec portfolios) -->
                           <div class="space-y-8">
                               @if($services->isEmpty())
                                   <p class="text-gray-500 text-center py-4">Aucun service disponible.</p>
                               @else
                                   @foreach($services as $service)
                                       @php
                                           // Récupérer le type de service correct selon la catégorie
                                           if ($activeCategory->name === 'negafa') {
                                               $serviceDetail = App\Models\Negafa::where('service_id', $service->id)->first();
                                               $portfolioItems = $serviceDetail ? App\Models\NegafaPortfolioItem::where('negafa_id', $serviceDetail->id)->get() : collect();
                                           } elseif ($activeCategory->name === 'maquillage') {
                                               $serviceDetail = App\Models\Makeup::where('service_id', $service->id)->first();
                                               $portfolioItems = $serviceDetail ? App\Models\MakeupPortfolioItem::where('makeup_id', $serviceDetail->id)->get() : collect();
                                           } elseif ($activeCategory->name === 'photographer') {
                                               $serviceDetail = App\Models\Photographer::where('service_id', $service->id)->first();
                                               $portfolioItems = $serviceDetail ? App\Models\PhotographerPortfolioItem::where('photographer_id', $serviceDetail->id)->get() : collect();
                                           }
                                       @endphp

                                       <div class="service-card border rounded-lg overflow-hidden">
                                           <div class="p-5 bg-gray-50 border-b">
                                               <div class="flex justify-between items-start">
                                                   <div>
                                                       <h3 class="text-xl font-semibold text-[#333333]">{{ $serviceDetail ? $serviceDetail->name : $service->title }}</h3>
                                                       @if($serviceDetail && $serviceDetail->experience)
                                                           <p class="text-sm text-gray-600 mt-1">
                                                               <span class="font-medium">Expérience:</span> {{ $serviceDetail->experience }}
                                                           </p>
                                                       @endif
                                                   </div>

                                                   <div class="custom-checkbox">
                                                       <input
                                                           type="checkbox"
                                                           id="{{ $activeCategory->name }}-{{ $service->id }}"
                                                           class="service-checkbox"
                                                           data-type="{{ $activeCategory->name }}"
                                                           data-id="{{ $service->id }}"
                                                           data-name="{{ $serviceDetail ? $serviceDetail->name : $service->title }}"
                                                           data-price="{{ $serviceDetail ? $serviceDetail->price : 0 }}"
                                                       >
                                                   </div>
                                               </div>
                                           </div>

                                           <div class="p-5">
                                               <div class="flex items-start">
                                                   @if($serviceDetail && $serviceDetail->photo)
                                                       <img src="{{ asset('storage/' . $serviceDetail->photo) }}" alt="{{ $serviceDetail->name }}" class="w-24 h-24 object-cover rounded-lg mr-4">
                                                   @endif
                                                   <div>
                                                       <p class="text-gray-600">
                                                           {{ $serviceDetail ? $serviceDetail->description : $service->description }}
                                                       </p>
                                                       <p class="text-[#C08081] font-medium mt-2">
                                                           {{ $serviceDetail ? number_format($serviceDetail->price, 2) : '0.00' }} MAD
                                                       </p>

                                                       @if(isset($portfolioItems) && $portfolioItems->isNotEmpty())
                                                           <button
                                                               class="mt-4 text-sm text-[#C08081] font-medium flex items-center view-portfolio-btn"
                                                               data-service-id="{{ $service->id }}"
                                                           >
                                                               <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                               </svg>
                                                               Voir le portfolio
                                                           </button>

                                                           <!-- Portfolio caché (sera affiché en modal) -->
                                                           <div class="hidden portfolio-items" id="portfolio-{{ $service->id }}">
                                                               @foreach($portfolioItems as $item)
                                                                   <div
                                                                       class="portfolio-item"
                                                                       data-type="{{ $item->type }}"
                                                                       data-path="{{ asset('storage/' . $item->file_path) }}"
                                                                       data-title="{{ $item->title }}"
                                                                       data-description="{{ $item->description }}"
                                                                   ></div>
                                                               @endforeach
                                                           </div>
                                                       @endif
                                                   </div>
                                               </div>
                                           </div>
                                       </div>
                                   @endforeach
                               @endif
                           </div>
                       @elseif($activeCategory->name === 'salles')
                           <!-- Affichage des salles -->
                           <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                               @if($services->isEmpty())
                                   <p class="text-gray-500 text-center py-4 col-span-full">Aucune salle disponible.</p>
                               @else
                                   @foreach($services as $service)
                                       @php
                                           $salle = App\Models\Salle::where('service_id', $service->id)->first();
                                       @endphp

                                       @if($salle)
                                           <div class="service-card border rounded-lg overflow-hidden">
                                               @if($salle->photo)
                                                   <img src="{{ asset('storage/' . $salle->photo) }}" alt="{{ $salle->name }}" class="w-full h-48 object-cover">
                                               @endif
                                               <div class="p-4">
                                                   <div class="flex justify-between items-start">
                                                       <div>
                                                           <h3 class="text-lg font-semibold text-[#333333]">{{ $salle->name }}</h3>
                                                           <p class="text-sm text-gray-600 mt-1">
                                                               <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                               </svg>
                                                               {{ $salle->location }}
                                                           </p>
                                                           <p class="text-sm text-gray-600 mt-1">
                                                               <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                               </svg>
                                                               {{ $salle->tables_count }} tables
                                                           </p>
                                                           @if($salle->description)
                                                               <p class="text-sm text-gray-600 mt-2">{{ $salle->description }}</p>
                                                           @endif
                                                           <p class="text-[#C08081] font-medium mt-2">{{ number_format($salle->price, 2) }} MAD</p>
                                                       </div>

                                                       <div class="custom-checkbox mt-1">
                                                           <input
                                                               type="checkbox"
                                                               id="salle-{{ $salle->id }}"
                                                               class="service-checkbox"
                                                               data-type="salle"
                                                               data-id="{{ $salle->id }}"
                                                               data-name="{{ $salle->name }}"
                                                               data-price="{{ $salle->price }}"
                                                           >
                                                       </div>
                                                   </div>
                                               </div>
                                           </div>
                                       @endif
                                   @endforeach
                               @endif
                           </div>
                       @elseif(in_array($activeCategory->name, ['decoration', 'amariya', 'animation']))
                           <!-- Affichage pour Decoration, Amariya, Animation -->
                           <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                               @if($services->isEmpty())
                                   <p class="text-gray-500 text-center py-4 col-span-full">Aucun service disponible.</p>
                               @else
                                   @foreach($services as $service)
                                       @php
                                           // Récupérer le type de service correct selon la catégorie
                                           if ($activeCategory->name === 'decoration') {
                                               $serviceDetail = App\Models\Decoration::where('service_id', $service->id)->first();
                                           } elseif ($activeCategory->name === 'amariya') {
                                               $serviceDetail = App\Models\Amariya::where('service_id', $service->id)->first();
                                           } elseif ($activeCategory->name === 'animation') {
                                               $serviceDetail = App\Models\Animation::where('service_id', $service->id)->first();
                                           }
                                       @endphp

                                       @if($serviceDetail)
                                           <div class="service-card border rounded-lg overflow-hidden">
                                               @if($serviceDetail->photo)
                                                   <img src="{{ asset('storage/' . $serviceDetail->photo) }}" alt="{{ $serviceDetail->name }}" class="w-full h-48 object-cover">
                                               @endif
                                               <div class="p-4">
                                                   <div class="flex justify-between items-start">
                                                       <div>
                                                           <h3 class="text-lg font-semibold text-[#333333]">{{ $serviceDetail->name }}</h3>
                                                           @if($activeCategory->name === 'animation' && isset($serviceDetail->type))
                                                               <p class="text-sm text-gray-600 mt-1">
                                                                   <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-xs">
                                                                       {{ ucfirst($serviceDetail->type) }}
                                                                   </span>
                                                               </p>
                                                           @endif
                                                           @if($serviceDetail->description)
                                                               <p class="text-sm text-gray-600 mt-2">{{ $serviceDetail->description }}</p>
                                                           @endif
                                                           <p class="text-[#C08081] font-medium mt-2">{{ number_format($serviceDetail->price, 2) }} MAD</p>
                                                       </div>

                                                       <div class="custom-checkbox mt-1">
                                                           <input
                                                               type="checkbox"
                                                               id="{{ $activeCategory->name }}-{{ $serviceDetail->id }}"
                                                               class="service-checkbox"
                                                               data-type="{{ $activeCategory->name }}"
                                                               data-id="{{ $serviceDetail->id }}"
                                                               data-name="{{ $serviceDetail->name }}"
                                                               data-price="{{ $serviceDetail->price }}"
                                                           >
                                                       </div>
                                                   </div>
                                               </div>
                                           </div>
                                       @endif
                                   @endforeach
                               @endif
                           </div>
                       @else
                           <p class="text-gray-500 text-center py-8">Sélectionnez une catégorie de service pour commencer.</p>
                       @endif
                   </div>
               </div>

               <!-- Barre latérale droite (Budget) -->
               <div class="lg:col-span-3">
                   <div class="bg-white rounded-xl shadow-sm p-6 budget-card">
                       <h2 class="text-xl font-semibold text-[#333333] mb-4">Votre Budget</h2>

                       <div class="space-y-4 mb-6">
                           <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                               <span class="text-gray-600">Total</span>
                               <span id="total-budget" class="font-semibold text-[#333333]">0.00 MAD</span>
                           </div>
                       </div>

                       <div id="selected-services" class="space-y-3 mb-6 max-h-80 overflow-y-auto">
                           <!-- Les services sélectionnés seront ajoutés ici dynamiquement -->
                           <p class="text-gray-500 text-center py-4" id="no-services-message">Aucun service sélectionné</p>
                       </div>

                       <form action="{{ route('planning.traiteur.reservation.store', ['traiteurId' => $traiteur->id]) }}" method="POST" id="reservation-form">
                           @csrf
                           <input type="hidden" name="event_date" value="{{ $date }}">
                           <input type="hidden" name="services_json" id="services-json" value="[]">

                           <button
                               type="submit"
                               id="submit-reservation-btn"
                               class="w-full bg-[#FADADD] hover:bg-[#C08081] text-[#333333] hover:text-white font-medium py-3 px-4 rounded-lg transition duration-300 opacity-50 cursor-not-allowed"
                               disabled
                           >
                               Valider et continuer
                           </button>
                       </form>
                   </div>
               </div>
           </div>
       </div>
   </div>
</div>

<!-- Modal pour le portfolio -->
<div id="portfolioModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center">
   <div class="bg-white rounded-xl w-full max-w-4xl p-6 relative max-h-[90vh] overflow-y-auto">
       <!-- Bouton fermer -->
       <button id="closePortfolioModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
           </svg>
       </button>

       <h3 id="portfolioTitle" class="text-2xl font-semibold text-[#333333] mb-6 text-center"></h3>

       <div id="portfolioContent" class="portfolio-gallery">
           <!-- Les éléments du portfolio seront ajoutés ici dynamiquement -->
       </div>
   </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
   // Variables pour stocker les services sélectionnés
   let selectedServices = [];
   let totalBudget = 0;

   // Éléments DOM
   const totalBudgetElement = document.getElementById('total-budget');
   const selectedServicesContainer = document.getElementById('selected-services');
   const noServicesMessage = document.getElementById('no-services-message');
   const submitReservationBtn = document.getElementById('submit-reservation-btn');
   const servicesJsonInput = document.getElementById('services-json');

   // Gestionnaire d'événement pour les onglets de menu
   const tabButtons = document.querySelectorAll('.tab-button');
   tabButtons.forEach(button => {
       button.addEventListener('click', function() {
           // Pour les onglets de menu
           if (this.dataset.menuId) {
               const menuId = this.dataset.menuId;
               const category = this.dataset.category;

               // Désactiver tous les onglets et contenus pour ce menu
               document.querySelectorAll(`[data-menu-id="${menuId}"]`).forEach(tab => {
                   tab.classList.remove('active');
               });

               document.querySelectorAll(`[class^="menu-items-${menuId}-"]`).forEach(content => {
                   content.classList.add('hidden');
               });

               // Activer l'onglet cliqué et son contenu
               this.classList.add('active');
               document.querySelector(`.menu-items-${menuId}-${category}`).classList.remove('hidden');
           }
           // Pour les onglets de style (vêtements)
           else if (this.dataset.style) {
               const style = this.dataset.style;

               // Désactiver tous les onglets et contenus
               document.querySelectorAll('[data-style]').forEach(tab => {
                   tab.classList.remove('active');
               });

               document.querySelectorAll('.style-content').forEach(content => {
                   content.classList.add('hidden');
               });

               // Activer l'onglet cliqué et son contenu
               this.classList.add('active');
               document.getElementById(`${style}-content`).classList.remove('hidden');
           }
       });
   });

   // Gestionnaire d'événement pour les checkbox de services
   const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
   serviceCheckboxes.forEach(checkbox => {
       checkbox.addEventListener('change', function() {
           const type = this.dataset.type;
           const id = this.dataset.id;
           const name = this.dataset.name;
           const price = parseFloat(this.dataset.price);
           const parentId = this.dataset.parentId; // Pour les éléments de menu

           if (this.checked) {
               // Ajouter le service à la liste des services sélectionnés
               selectedServices.push({
                   type: type,
                   id: id,
                   name: name,
                   price: price,
                   parent_id: parentId
               });

               // Mettre à jour le total
               totalBudget += price;

               // Ajouter l'élément à la liste affichée
               addServiceToList(type, id, name, price);
           } else {
               // Trouver l'index du service dans le tableau
               const index = selectedServices.findIndex(service =>
                   service.type === type && service.id === id
               );

               if (index !== -1) {
                   // Retirer le service de la liste des services sélectionnés
                   totalBudget -= selectedServices[index].price;
                   selectedServices.splice(index, 1);

                   // Retirer l'élément de la liste affichée
                   removeServiceFromList(type, id);
               }
           }

           // Mettre à jour l'affichage du total
           updateTotalDisplay();

           // Mettre à jour l'input caché pour le formulaire
           updateServicesJson();

           // Vérifier si des services sont sélectionnés pour activer le bouton de soumission
           checkSubmitButton();
       });
   });

   // Gestionnaire d'événement pour les boutons "Voir le portfolio"
   const portfolioButtons = document.querySelectorAll('.view-portfolio-btn');
   const portfolioModal = document.getElementById('portfolioModal');
   const closePortfolioModal = document.getElementById('closePortfolioModal');
   const portfolioTitle = document.getElementById('portfolioTitle');
   const portfolioContent = document.getElementById('portfolioContent');

   portfolioButtons.forEach(button => {
       button.addEventListener('click', function() {
           const serviceId = this.dataset.serviceId;
           const portfolioItems = document.getElementById(`portfolio-${serviceId}`).querySelectorAll('.portfolio-item');

           // Définir le titre du modal
           portfolioTitle.textContent = `Portfolio de ${this.closest('.service-card').querySelector('h3').textContent}`;

           // Vider le contenu précédent
           portfolioContent.innerHTML = '';

           // Remplir avec les éléments du portfolio
           portfolioItems.forEach(item => {
               const type = item.dataset.type;
               const path = item.dataset.path;
               const title = item.dataset.title;
               const description = item.dataset.description;

               const galleryItem = document.createElement('div');
               galleryItem.className = 'gallery-item';

               if (type === 'image') {
                   galleryItem.innerHTML = `
                       <img src="${path}" alt="${title || 'Image de portfolio'}">
                       ${title ? `<div class="p-2 text-sm font-medium">${title}</div>` : ''}
                       ${description ? `<div class="p-2 text-xs text-gray-600">${description}</div>` : ''}
                   `;
               } else if (type === 'video') {
                   galleryItem.innerHTML = `
                       <video src="${path}" controls class="w-full h-auto"></video>
                       ${title ? `<div class="p-2 text-sm font-medium">${title}</div>` : ''}
                       ${description ? `<div class="p-2 text-xs text-gray-600">${description}</div>` : ''}
                   `;
               }

               portfolioContent.appendChild(galleryItem);
           });

           // Afficher le modal
           portfolioModal.classList.remove('hidden');
       });
   });

   // Fermer le modal de portfolio
   closePortfolioModal.addEventListener('click', function() {
       portfolioModal.classList.add('hidden');
   });

   // Fonction pour ajouter un service à la liste affichée
   function addServiceToList(type, id, name, price) {
       // Cacher le message "Aucun service sélectionné"
       noServicesMessage.classList.add('hidden');

       // Créer l'élément pour le service
       const serviceElement = document.createElement('div');
       serviceElement.className = 'budget-item flex justify-between items-center p-3 bg-gray-50 rounded-lg selected-animation';
       serviceElement.id = `service-item-${type}-${id}`;
       serviceElement.innerHTML = `
           <span class="text-gray-700">${name}</span>
           <div class="flex items-center">
               <span class="font-medium text-[#C08081] mr-3">${price.toFixed(2)} MAD</span>
               <button
                   class="text-gray-400 hover:text-red-500 remove-service"
                   data-type="${type}"
                   data-id="${id}"
               >
                   <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                   </svg>
               </button>
           </div>
       `;

       // Ajouter l'élément au conteneur
       selectedServicesContainer.appendChild(serviceElement);

       // Ajouter l'événement pour le bouton de suppression
       serviceElement.querySelector('.remove-service').addEventListener('click', function() {
           const type = this.dataset.type;
           const id = this.dataset.id;

           // Décocher la checkbox
           const checkbox = document.getElementById(`${type}-${id}`);
           if (checkbox) {
               checkbox.checked = false;

               // Déclencher l'événement change pour mettre à jour la liste
               const event = new Event('change');
               checkbox.dispatchEvent(event);
           } else {
               // Si on ne trouve pas la checkbox (cas rare), on retire manuellement
               removeServiceFromList(type, id);

               // Trouver l'index du service dans le tableau et le supprimer
               const index = selectedServices.findIndex(service =>
                   service.type === type && service.id === id
               );

               if (index !== -1) {
                   totalBudget -= selectedServices[index].price;
                   selectedServices.splice(index, 1);

                   // Mettre à jour l'affichage du total et le JSON
                   updateTotalDisplay();
                   updateServicesJson();
                   checkSubmitButton();
               }
           }
       });
   }

   // Fonction pour retirer un service de la liste affichée
   function removeServiceFromList(type, id) {
       const serviceElement = document.getElementById(`service-item-${type}-${id}`);
       if (serviceElement) {
           serviceElement.remove();
       }

       // Afficher le message "Aucun service sélectionné" si la liste est vide
       if (selectedServices.length === 0) {
           noServicesMessage.classList.remove('hidden');
       }
   }

   // Fonction pour mettre à jour l'affichage du total
   function updateTotalDisplay() {
       totalBudgetElement.textContent = `${totalBudget.toFixed(2)} MAD`;

       // Ajouter un effet visuel pour le changement
       totalBudgetElement.classList.add('text-[#C08081]', 'font-bold');

       setTimeout(() => {
           totalBudgetElement.classList.remove('text-[#C08081]', 'font-bold');
       }, 300);
   }

   // Fonction pour mettre à jour l'input JSON pour le formulaire
   function updateServicesJson() {
       servicesJsonInput.value = JSON.stringify(selectedServices);
   }

   // Fonction pour vérifier si le bouton de soumission doit être activé
   function checkSubmitButton() {
       if (selectedServices.length > 0) {
           submitReservationBtn.disabled = false;
           submitReservationBtn.classList.remove('opacity-50', 'cursor-not-allowed');
       } else {
           submitReservationBtn.disabled = true;
           submitReservationBtn.classList.add('opacity-50', 'cursor-not-allowed');
       }
   }

   // Validation du formulaire avant soumission
   document.getElementById('reservation-form').addEventListener('submit', function(e) {
       if (selectedServices.length === 0) {
        e.preventDefault();
           alert('Veuillez sélectionner au moins un service avant de continuer.');
       }
   });
});
</script>
@endsection

