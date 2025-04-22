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

    /* Style pour les boutons radio personnalisés */
    .custom-radio {
        display: flex;
        align-items: center;
    }

    .custom-radio input[type="radio"] {
        appearance: none;
        -webkit-appearance: none;
        width: 22px;
        height: 22px;
        border: 2px solid #FADADD;
        border-radius: 50%;
        outline: none;
        cursor: pointer;
        position: relative;
        transition: all 0.2s ease;
    }

    .custom-radio input[type="radio"]:checked {
        border-color: #C08081;
    }

    .custom-radio input[type="radio"]:checked::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: #C08081;
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

                    <div class="mt-4 md:flex items-center space-x-4">
                        <div class="mb-4 md:mb-0">
                            <label for="nombre_invites" class="block text-sm font-medium text-gray-700 mb-1">Nombre d'invités</label>
                            <div class="flex items-center">
                                <input
                                    type="number"
                                    id="nombre_invites"
                                    name="nombre_invites"
                                    min="1"
                                    placeholder="Ex: 100"
                                    class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]"
                                >
                                <span class="ml-2 text-sm text-gray-500" id="tables_count">(0 tables)</span>
                            </div>
                        </div>

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
                                                                                data-category="{{ $item->category }}"
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
                                                    $robes = isset($categoryData['clothingItems'])
                                                        ? $categoryData['clothingItems']->where('style', 'traditionnel')
                                                                                     ->where('category', 'robe_mariee')
                                                        : collect();
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
                                                                            data-subtype="robe_mariee"
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
                                                    $costumes = isset($categoryData['clothingItems'])
                                                        ? $categoryData['clothingItems']->where('style', 'traditionnel')
                                                                                     ->where('category', 'costume_homme')
                                                        : collect();
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
                                                                        data-subtype="costume_homme"
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
                                                    $robesModernes = isset($categoryData['clothingItems'])
                                                        ? $categoryData['clothingItems']->where('style', 'moderne')
                                                                                     ->where('category', 'robe_mariee')
                                                        : collect();
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
                                                   $costumesModernes = isset($categoryData['clothingItems'])
                                                       ? $categoryData['clothingItems']->where('style', 'moderne')
                                                                                    ->where('category', 'costume_homme')
                                                       : collect();
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
                               @php
                                   // Déterminer quelle collection utiliser selon la catégorie active
                                   $items = collect();
                                   if ($activeCategory->name === 'negafa' && isset($categoryData['negafas'])) {
                                       $items = $categoryData['negafas'];
                                   } elseif ($activeCategory->name === 'maquillage' && isset($categoryData['makeups'])) {
                                       $items = $categoryData['makeups'];
                                   } elseif ($activeCategory->name === 'photographer' && isset($categoryData['photographers'])) {
                                       $items = $categoryData['photographers'];
                                   }
                               @endphp

                               @if($items->isEmpty())
                                   <p class="text-gray-500 text-center py-4">Aucun service disponible.</p>
                               @else
                                   @foreach($items as $item)
                                       <div class="service-card border rounded-lg overflow-hidden">
                                           <div class="p-5 bg-gray-50 border-b">
                                               <div class="flex justify-between items-start">
                                                   <div>
                                                       <h3 class="text-xl font-semibold text-[#333333]">{{ $item->name }}</h3>
                                                       @if($item->experience)
                                                           <p class="text-sm text-gray-600 mt-1">
                                                               <span class="font-medium">Expérience:</span> {{ $item->experience }}
                                                           </p>
                                                       @endif
                                                   </div>

                                                   <div class="custom-radio">
                                                       <input
                                                           type="radio"
                                                           name="{{ $activeCategory->name }}-selection"
                                                           id="{{ $activeCategory->name }}-{{ $item->id }}"
                                                           class="service-radio"
                                                           data-type="{{ $activeCategory->name }}"
                                                           data-id="{{ $item->id }}"
                                                           data-name="{{ $item->name }}"
                                                           data-price="{{ $item->price }}"
                                                       >
                                                   </div>
                                               </div>
                                           </div>

                                           <div class="p-5">
                                               <div class="flex items-start">
                                                   @if($item->photo)
                                                       <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->name }}" class="w-24 h-24 object-cover rounded-lg mr-4">
                                                   @endif
                                                   <div>
                                                       <p class="text-gray-600">
                                                           {{ $item->description }}
                                                       </p>
                                                       <p class="text-[#C08081] font-medium mt-2">
                                                           {{ number_format($item->price, 2) }} MAD
                                                       </p>

                                                       @if(isset($item->portfolioItems) && $item->portfolioItems->isNotEmpty())
                                                           <button
                                                               class="mt-4 text-sm text-[#C08081] font-medium flex items-center view-portfolio-btn"
                                                               data-service-id="{{ $item->id }}"
                                                           >
                                                               <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                               </svg>
                                                               Voir le portfolio
                                                           </button>

                                                           <!-- Portfolio caché (sera affiché en modal) -->
                                                           <div class="hidden portfolio-items" id="portfolio-{{ $item->id }}">
                                                               @foreach($item->portfolioItems as $portfolioItem)
                                                                   <div
                                                                       class="portfolio-item"
                                                                       data-type="{{ $portfolioItem->type }}"
                                                                       data-path="{{ asset('storage/' . $portfolioItem->file_path) }}"
                                                                       data-title="{{ $portfolioItem->title }}"
                                                                       data-description="{{ $portfolioItem->description }}"
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
                               @php
                                   $salles = isset($categoryData['salles']) ? $categoryData['salles'] : collect();
                               @endphp

                               @if($salles->isEmpty())
                                   <p class="text-gray-500 text-center py-4 col-span-full">Aucune salle disponible.</p>
                               @else
                                   @foreach($salles as $salle)
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

                                                   <div class="custom-radio mt-1">
                                                       <input
                                                           type="radio"
                                                           name="salle-selection"
                                                           id="salle-{{ $salle->id }}"
                                                           class="service-radio"
                                                           data-type="salle"
                                                           data-id="{{ $salle->id }}"
                                                           data-name="{{ $salle->name }}"
                                                           data-price="{{ $salle->price }}"
                                                           data-tables="{{ $salle->tables_count }}"
                                                       >
                                                   </div>
                                               </div>
                                           </div>
                                       </div>
                                   @endforeach
                               @endif
                           </div>
                       @elseif(in_array($activeCategory->name, ['decoration', 'amariya', 'animation']))
                           <!-- Affichage pour Decoration, Amariya, Animation -->
                           <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                               @php
                                   // Déterminer quelle collection utiliser selon la catégorie active
                                   $items = collect();
                                   if ($activeCategory->name === 'decoration' && isset($categoryData['decorations'])) {
                                       $items = $categoryData['decorations'];
                                   } elseif ($activeCategory->name === 'amariya' && isset($categoryData['amariyas'])) {
                                       $items = $categoryData['amariyas'];
                                   } elseif ($activeCategory->name === 'animation' && isset($categoryData['animations'])) {
                                       $items = $categoryData['animations'];
                                   }
                               @endphp

                               @if($items->isEmpty())
                                   <p class="text-gray-500 text-center py-4 col-span-full">Aucun service disponible.</p>
                               @else
                                   @foreach($items as $item)
                                       <div class="service-card border rounded-lg overflow-hidden">
                                           @if($item->photo)
                                               <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->name }}" class="w-full h-48 object-cover">
                                           @endif
                                           <div class="p-4">
                                               <div class="flex justify-between items-start">
                                                   <div>
                                                       <h3 class="text-lg font-semibold text-[#333333]">{{ $item->name }}</h3>
                                                       @if($activeCategory->name === 'animation' && isset($item->type))
                                                           <p class="text-sm text-gray-600 mt-1">
                                                               <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-xs">
                                                                   {{ ucfirst($item->type) }}
                                                               </span>
                                                           </p>
                                                       @endif
                                                       @if($item->description)
                                                           <p class="text-sm text-gray-600 mt-2">{{ $item->description }}</p>
                                                       @endif
                                                       <p class="text-[#C08081] font-medium mt-2">{{ number_format($item->price, 2) }} MAD</p>
                                                   </div>

                                                   <div class="custom-radio mt-1">
                                                       <input
                                                           type="radio"
                                                           name="{{ $activeCategory->name }}-selection"
                                                           id="{{ $activeCategory->name }}-{{ $item->id }}"
                                                           class="service-radio"
                                                           data-type="{{ $activeCategory->name }}"
                                                           data-id="{{ $item->id }}"
                                                           data-name="{{ $item->name }}"
                                                           data-price="{{ $item->price }}"
                                                       >
                                                   </div>
                                               </div>
                                           </div>
                                       </div>
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


                       @if(session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                       <form action="{{ route('reservation.store', ['traiteurId' => $traiteur->id]) }}" method="POST" id="reservation-form">
                        @csrf
                        <input type="hidden" name="event_date" value="{{ $date }}">
                        <input type="hidden" name="services_json" id="services-json" value="[]">
                        <input type="hidden" name="nombre_invites" id="nombre_invites_hidden" value="">
                        <input type="hidden" name="nombre_tables" id="nombre_tables_hidden" value="">

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

<!-- Modal pour les erreurs de validation -->
<div id="validationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl w-full max-w-md p-6 relative">
        <!-- Bouton fermer -->
        <button id="closeValidationModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="text-center mb-4">
            <div class="bg-red-100 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-red-600 mb-2">Erreur de validation</h3>
        </div>

        <div id="validationErrorMessage" class="text-gray-700 mb-6">

        </div>

        <div class="mt-6 text-center">
            <button id="confirmValidationModal" class="bg-[#FADADD] hover:bg-[#C08081] text-[#333333] hover:text-white font-medium py-2 px-6 rounded-lg transition duration-300">
                J'ai compris
            </button>
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
    let nombreInvites = 0;
    let nombreTables = 0;

    // Éléments DOM
    const totalBudgetElement = document.getElementById('total-budget');
    const selectedServicesContainer = document.getElementById('selected-services');
    const noServicesMessage = document.getElementById('no-services-message');
    const submitReservationBtn = document.getElementById('submit-reservation-btn');
    const servicesJsonInput = document.getElementById('services-json');

    const nombreInvitesInput = document.getElementById('nombre_invites');
    const nombreInvitesHidden = document.getElementById('nombre_invites_hidden');
    const nombreTablesHidden = document.getElementById('nombre_tables_hidden');
    const tablesCountDisplay = document.getElementById('tables_count');

    const validationModal = document.getElementById('validationModal');
    const closeValidationModal = document.getElementById('closeValidationModal');
    const confirmValidationModal = document.getElementById('confirmValidationModal');
    const validationErrorMessage = document.getElementById('validationErrorMessage');

    // Fonction pour afficher les erreurs de validation
    function showValidationError(message) {
        validationErrorMessage.innerHTML = message;
        validationModal.classList.remove('hidden');
    }

    // Fonction pour valider la sélection des services
    function validateServiceSelection() {
        const requiredTypes = {
            'menu': 'Menu',
            'menu-item': 'Menu ou Plat',
            'salle': 'Salle',
            'vetement': 'Vêtements',
            'negafa': 'Négafa',
            'maquillage': 'Maquillage',
            'photographer': 'Photographe',
            'decoration': 'Décoration',
            'amariya': 'Amariya',
            'animation': 'Animation'
        };

        if (selectedServices.length === 0) {
            showValidationError("Vous n'avez sélectionné aucun service. Veuillez choisir au moins un service dans chaque catégorie obligatoire.");
            return false;
        }

        const missingTypes = [];

        // Vérifier chaque type requis
        for (const [type, displayName] of Object.entries(requiredTypes)) {
            let hasType = false;

            // Vérifier si ce type est présent dans les services sélectionnés
            for (const service of selectedServices) {
                if (service.type === type || (type === 'menu-item' && service.type === 'menu')) {
                    hasType = true;
                    break;
                }
            }

            // Si le type est manquant, l'ajouter à la liste
            if (!hasType && type !== 'menu') { // Exclure 'menu' de la validation car 'menu-item' est suffisant
                missingTypes.push(displayName);
            }
        }

        // Si des types sont manquants, afficher l'erreur
        if (missingTypes.length > 0) {
            const errorMessage = `Veuillez sélectionner au moins un élément dans ${missingTypes.length > 1 ? 'chacune des' : 'la'} catégorie${missingTypes.length > 1 ? 's' : ''} suivante${missingTypes.length > 1 ? 's' : ''} : <br><br><ul class="list-disc pl-5">` +
                missingTypes.map(type => `<li>${type}</li>`).join('') +
                '</ul>';
            showValidationError(errorMessage);
            return false;
        }

        return true;
    }

    // Charger les services sélectionnés précédemment depuis localStorage
    if (localStorage.getItem('selectedServices')) {
        try {
            selectedServices = JSON.parse(localStorage.getItem('selectedServices'));
            totalBudget = parseFloat(localStorage.getItem('totalBudget') || 0);

            // Mettre à jour l'affichage
            updateTotalDisplay();
            updateServicesJson();

            // Recréer les éléments visuels pour les services sélectionnés
            if (selectedServices.length > 0) {
                noServicesMessage.classList.add('hidden');
                selectedServices.forEach(service => {
                    addServiceToList(service.type, service.id, service.name, service.price);
                });

                // Activer le bouton de soumission
                checkSubmitButton();

                // Mettre à jour les checkboxes et radios pour refléter la sélection
                setTimeout(() => {
                    selectedServices.forEach(service => {
                        // Pour les checkboxes
                        const checkbox = document.getElementById(`${service.type}-${service.id}`);
                        if (checkbox && checkbox.type === 'checkbox') {
                            checkbox.checked = true;
                        } else {
                            // Pour les boutons radio
                            const radio = document.getElementById(`${service.type}-${service.id}`);
                            if (radio && radio.type === 'radio') {
                                radio.checked = true;
                            }
                        }
                    });
                }, 100);
            }
        } catch (e) {
            console.error("Erreur lors du chargement des services sélectionnés:", e);
            // Réinitialiser en cas d'erreur
            localStorage.removeItem('selectedServices');
            localStorage.removeItem('totalBudget');
        }
    }

    // Gestion du nombre d'invités et calcul du nombre de tables
    nombreInvitesInput.addEventListener('input', function() {
        nombreInvites = parseInt(this.value) || 0;
        nombreInvitesHidden.value = nombreInvites;

        // Calcul du nombre de tables
        nombreTables = Math.floor(nombreInvites / 10);
        if (nombreInvites % 10 > 5) {
            nombreTables += 1;
        }

        // Mise à jour des champs cachés et de l'affichage
        nombreTablesHidden.value = nombreTables;
        tablesCountDisplay.textContent = `(${nombreTables} tables)`;

        // Mettre à jour l'affichage du budget car le prix peut dépendre du nombre d'invités/tables
        recalculateTotalBudget();

        // Vérifier la disponibilité des salles
        checkSalleTables();

        // Sauvegarder dans localStorage
        localStorage.setItem('nombreInvites', nombreInvites);
        localStorage.setItem('nombreTables', nombreTables);
    });

    // Charger les valeurs depuis localStorage
    if (localStorage.getItem('nombreInvites')) {
        nombreInvites = parseInt(localStorage.getItem('nombreInvites'));
        nombreTables = parseInt(localStorage.getItem('nombreTables')) || 0;

        nombreInvitesInput.value = nombreInvites;
        nombreInvitesHidden.value = nombreInvites;
        nombreTablesHidden.value = nombreTables;
        tablesCountDisplay.textContent = `(${nombreTables} tables)`;
    }

    // Fonction pour vérifier si les salles ont assez de tables
    function checkSalleTables() {
        const salleRadios = document.querySelectorAll('input[data-type="salle"]');

        salleRadios.forEach(radio => {
            const salleTableCount = parseInt(radio.dataset.tables) || 0;

            if (salleTableCount < nombreTables) {
                // Désactiver la salle si elle n'a pas assez de tables
                radio.disabled = true;
                radio.parentElement.parentElement.parentElement.parentElement.classList.add('opacity-50');

                // Si cette salle était sélectionnée, la désélectionner
                if (radio.checked) {
                    radio.checked = false;

                    // Retirer de la liste des services sélectionnés
                    const index = selectedServices.findIndex(service =>
                        service.type === "salle" && service.id === radio.dataset.id
                    );

                    if (index !== -1) {
                        totalBudget -= selectedServices[index].price;
                        removeServiceFromList("salle", radio.dataset.id);
                        selectedServices.splice(index, 1);

                        // Mettre à jour l'affichage et le stockage
                        updateTotalDisplay();
                        updateServicesJson();
                        localStorage.setItem('selectedServices', JSON.stringify(selectedServices));
                        localStorage.setItem('totalBudget', totalBudget.toString());
                    }
                }
            } else {
                radio.disabled = false;
                radio.parentElement.parentElement.parentElement.parentElement.classList.remove('opacity-50');
            }
        });
    }

    // Fonction pour recalculer le budget total basé sur le nombre d'invités/tables
    function recalculateTotalBudget() {
        totalBudget = 0;

        // Recalculer le prix pour chaque service
        selectedServices.forEach(service => {
            let price = parseFloat(service.originalPrice || service.price);

            // Ajuster le prix selon les règles
            if (service.type === 'menu-item') {
                if (service.category === 'plat') {
                    // Prix x nombre de tables pour les plats
                    price *= nombreTables;
                } else {
                    // Prix x nombre d'invités pour les autres items du menu
                    price *= nombreInvites;
                }
            }

            // Mettre à jour le prix dans l'objet service
            service.price = price;
            totalBudget += price;
        });

        // Mettre à jour l'affichage
        updateTotalDisplay();
        updateServicesJson();
        localStorage.setItem('selectedServices', JSON.stringify(selectedServices));
        localStorage.setItem('totalBudget', totalBudget.toString());
    }

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

    // Gestionnaire d'événement pour les checkboxes de services
    const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
    serviceCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            handleServiceSelection(this, false);
        });
    });

    // Gestionnaire d'événement pour les boutons radio de services
    const serviceRadios = document.querySelectorAll('.service-radio');
    serviceRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            handleServiceSelection(this, true);
        });
    });

    // Fonction pour gérer la sélection des services (checkbox ou radio)
    function handleServiceSelection(element, isRadio) {
        const type = element.dataset.type;
        const id = element.dataset.id;
        const name = element.dataset.name;
        const price = parseFloat(element.dataset.price);
        const parentId = element.dataset.parentId;
        const tables = element.dataset.tables;
        const category = element.dataset.category; // Pour les items de menu

        // Vérifier les limites de sélection
        if (element.checked) {
            // Vérifications des limites avant d'ajouter
            let canAdd = true;

            if (type === 'menu-item' && category === 'plat') {
                // Vérifier le nombre de plats
                const platsCount = selectedServices.filter(s => s.type === 'menu-item' && s.category === 'plat').length;
                if (platsCount >= 2) {
                    alert('Vous ne pouvez sélectionner que 2 plats maximum.');
                    element.checked = false;
                    return;
                }
            } else if (type === 'vetement') {
                // Vérifier les limites pour les vêtements
                const isRobe = element.dataset.subtype === 'robe_mariee';
                const isCostume = element.dataset.subtype === 'costume_homme';

                if (isRobe) {
                    const robesCount = selectedServices.filter(s => s.type === 'vetement' && s.subtype === 'robe_mariee').length;
                    if (robesCount >= 2) {
                        alert('Vous ne pouvez sélectionner que 2 robes maximum.');
                        element.checked = false;
                        return;
                    }
                } else if (isCostume) {
                    const costumesCount = selectedServices.filter(s => s.type === 'vetement' && s.subtype === 'costume_homme').length;
                    if (costumesCount >= 2) {
                        alert('Vous ne pouvez sélectionner que 2 costumes maximum.');
                        element.checked = false;
                        return;
                    }
                }
            } else if (type === 'amariya') {
                // Vérifier la limite des amariya
                const amariyasCount = selectedServices.filter(s => s.type === 'amariya').length;
                if (amariyasCount >= 2) {
                    alert('Vous ne pouvez sélectionner que 2 amariyas maximum.');
                    element.checked = false;
                    return;
                }
            } else if (type === 'animation') {
                // Vérifier la limite des animations
                const animationsCount = selectedServices.filter(s => s.type === 'animation').length;
                if (animationsCount >= 2) {
                    alert('Vous ne pouvez sélectionner que 2 animations maximum.');
                    element.checked = false;
                    return;
                }
            }

            // Si c'est un radio, on retire d'abord tous les autres services du même type
            if (isRadio) {
                const existingServices = selectedServices.filter(service => service.type === type);

                existingServices.forEach(service => {
                    // Retirer du budget total
                    totalBudget -= service.price;

                    // Retirer de la liste affichée
                    removeServiceFromList(service.type, service.id);

                    // Retirer du tableau des services sélectionnés
                    const index = selectedServices.findIndex(s =>
                        s.type === service.type && s.id === service.id
                    );

                    if (index !== -1) {
                        selectedServices.splice(index, 1);
                    }
                });
            }

            // Ajouter le service à la liste des services sélectionnés
            let adjustedPrice = price;

            // Ajuster le prix selon les règles
            if (type === 'menu-item') {
                if (category === 'plat') {
                    // Prix x nombre de tables pour les plats
                    adjustedPrice *= nombreTables;
                } else {
                    // Prix x nombre d'invités pour les autres items du menu
                    adjustedPrice *= nombreInvites;
                }
            }

            const serviceData = {
                type: type,
                id: id,
                name: name,
                originalPrice: price,
                price: adjustedPrice,
                parent_id: parentId,
                tables: tables,
                category: category,
                subtype: element.dataset.subtype
            };

            selectedServices.push(serviceData);

            // Mettre à jour le total
            totalBudget += adjustedPrice;

            // Ajouter l'élément à la liste affichée
            addServiceToList(type, id, name, adjustedPrice);
        } else {
            // Seulement pour les checkboxes (les radios ne peuvent pas être décochés directement)
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

        // Sauvegarder dans localStorage
        localStorage.setItem('selectedServices', JSON.stringify(selectedServices));
        localStorage.setItem('totalBudget', totalBudget.toString());

        // Vérifier si des services sont sélectionnés pour activer le bouton de soumission
        checkSubmitButton();
    }

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
                const title = item.dataset.title || '';
                const description = item.dataset.description || '';

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

        // Vérifier si le service existe déjà dans la liste
        const existingElement = document.getElementById(`service-item-${type}-${id}`);
        if (existingElement) {
            return; // Éviter les doublons
        }

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

            // Décocher la checkbox ou le radio
            const input = document.getElementById(`${type}-${id}`) || document.getElementById(`item-${id}`);
            if (input) {
                input.checked = false;

                if (input.type === 'checkbox') {
                    // Déclencher l'événement change pour les checkboxes
                    const event = new Event('change');
                    input.dispatchEvent(event);
                } else if (input.type === 'radio') {
                    // Pour les radios, on doit faire la mise à jour manuellement
                    // Trouver l'index du service dans le tableau
                    const index = selectedServices.findIndex(service =>
                        service.type === type && service.id === id
                    );

                    if (index !== -1) {
                        totalBudget -= selectedServices[index].price;
                        selectedServices.splice(index, 1);

                        // Mettre à jour l'affichage du total et le JSON
                        updateTotalDisplay();
                        updateServicesJson();

                        // Sauvegarder dans localStorage
                        localStorage.setItem('selectedServices', JSON.stringify(selectedServices));
                        localStorage.setItem('totalBudget', totalBudget.toString());

                        // Retirer l'élément de la liste
                        removeServiceFromList(type, id);

                        checkSubmitButton();
                    }
                }
            } else {
                // Si on ne trouve pas l'input, on retire manuellement
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

                    // Sauvegarder dans localStorage
                    localStorage.setItem('selectedServices', JSON.stringify(selectedServices));
                    localStorage.setItem('totalBudget', totalBudget.toString());

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

    // Initialiser la vérification des salles au chargement de la page
    checkSalleTables();

    // Validation du formulaire avant soumission
    document.getElementById('reservation-form').addEventListener('submit', function(e) {
        e.preventDefault(); // Empêcher la soumission par défaut

        // Vérifier le nombre d'invités
        if (!nombreInvites || nombreInvites <= 0) {
            showValidationError('Veuillez indiquer le nombre d\'invités.');
            return;
        }

        // Valider la sélection des services
        if (!validateServiceSelection()) {
            return; // Arrêter si la validation échoue
        }

        // Mettre à jour les champs cachés avant la soumission
        nombreInvitesHidden.value = nombreInvites;
        nombreTablesHidden.value = nombreTables;

        // Si tout est valide, soumettre le formulaire
        this.submit();

        // Effacer le localStorage
        localStorage.removeItem('selectedServices');
        localStorage.removeItem('totalBudget');
        localStorage.removeItem('nombreInvites');
        localStorage.removeItem('nombreTables');
    });

    // Gestionnaires pour le modal de validation
    closeValidationModal.addEventListener('click', function() {
        validationModal.classList.add('hidden');
    });

    confirmValidationModal.addEventListener('click', function() {
        validationModal.classList.add('hidden');
    });
});
</script>
@endsection
