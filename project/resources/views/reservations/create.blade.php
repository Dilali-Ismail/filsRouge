@extends('layouts.app')

@section('title', 'Créer une réservation')

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
                    <h1 class="text-3xl font-bold text-[#333333] font-display">Créer une réservation</h1>
                </div>
                <p class="text-gray-600 mt-2">Sélectionnez les services que vous souhaitez réserver pour votre mariage.</p>
            </div>

            <!-- Informations sur la réservation -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-[#333333]">Réservation avec {{ $traiteur->manager_name }}</h2>
                        <p class="text-gray-600">Pour le {{ \Carbon\Carbon::parse($eventDate)->format('d/m/Y') }}</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <div class="bg-gray-100 px-4 py-2 rounded-lg">
                            <p class="text-gray-700">Budget total: <span id="totalBudget" class="font-semibold text-[#C08081]">0.00 DH</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('reservations.store') }}" method="POST" id="reservationForm">
                @csrf
                <input type="hidden" name="traiteur_id" value="{{ $traiteur->id }}">
                <input type="hidden" name="event_date" value="{{ $eventDate }}">

                <!-- Sélection des services -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                    <h2 class="text-xl font-semibold text-[#333333] mb-6">Sélectionnez vos services</h2>

                    <div class="space-y-8">
                        <!-- Menu -->
                        @php
                            $menuServices = $traiteur->services->where('category.name', 'menu');
                        @endphp
                        @if($menuServices->count() > 0)
                            <div class="service-section">
                                <h3 class="text-lg font-semibold text-[#333333] mb-3">Menu</h3>

                                @foreach($menuServices as $service)
                                    <div class="mb-6">
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" id="service_{{ $service->id }}" name="services[]" value="{{ $service->id }}" class="service-checkbox h-5 w-5 text-[#C08081] rounded focus:ring-[#FADADD]">
                                            <label for="service_{{ $service->id }}" class="ml-2 text-gray-700">{{ $service->title }}</label>
                                        </div>
                                        <div class="ml-7 service-items hidden">
                                            @if($service->menuItems->count() > 0)
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-3">
                                                    @foreach($service->menuItems as $item)
                                                        <div class="bg-gray-50 p-3 rounded-lg">
                                                            <div class="flex justify-between items-start mb-2">
                                                                <div>
                                                                    <h4 class="font-medium text-gray-800">{{ $item->name }}</h4>
                                                                    <p class="text-xs text-gray-500">{{ ucfirst($item->category) }}</p>
                                                                </div>
                                                                <span class="text-[#C08081] font-medium">{{ number_format($item->price, 2) }} DH</span>
                                                            </div>
                                                            <div class="flex items-center justify-between">
                                                                <div class="flex-1">
                                                                    <p class="text-xs text-gray-600 line-clamp-2">{{ $item->description ?? 'Aucune description' }}</p>
                                                                </div>
                                                                <div class="flex items-center">
                                                                    <button type="button" class="decrement-qty bg-gray-200 text-gray-600 h-7 w-7 rounded-l-md flex items-center justify-center">-</button>
                                                                    <input type="number" name="items[{{ $service->id }}][{{ $item->id }}]" value="0" min="0" class="item-qty h-7 w-12 text-center border-y border-gray-200 focus:outline-none text-sm">
                                                                    <button type="button" class="increment-qty bg-gray-200 text-gray-600 h-7 w-7 rounded-r-md flex items-center justify-center">+</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-gray-500 italic">Aucun plat disponible pour ce menu.</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Décoration -->
                        @php
                            $decorationServices = $traiteur->services->where('category.name', 'decoration');
                        @endphp
                        @if($decorationServices->count() > 0)
                            <div class="service-section">
                                <h3 class="text-lg font-semibold text-[#333333] mb-3">Décoration</h3>

                                @foreach($decorationServices as $service)
                                    <div class="mb-6">
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" id="service_{{ $service->id }}" name="services[]" value="{{ $service->id }}" class="service-checkbox h-5 w-5 text-[#C08081] rounded focus:ring-[#FADADD]">
                                            <label for="service_{{ $service->id }}" class="ml-2 text-gray-700">{{ $service->title }}</label>
                                        </div>
                                        <div class="ml-7 service-items hidden">
                                            @if($service->decorations->count() > 0)
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-3">
                                                    @foreach($service->decorations as $item)
                                                        <div class="bg-gray-50 p-3 rounded-lg">
                                                            <div class="flex justify-between items-start mb-2">
                                                                <div>
                                                                    <h4 class="font-medium text-gray-800">{{ $item->name }}</h4>
                                                                </div>
                                                                <span class="text-[#C08081] font-medium">{{ number_format($item->price, 2) }} DH</span>
                                                            </div>
                                                            <div class="flex items-center justify-between">
                                                                <div class="flex-1">
                                                                    <p class="text-xs text-gray-600 line-clamp-2">{{ $item->description ?? 'Aucune description' }}</p>
                                                                </div>
                                                                <div class="flex items-center">
                                                                    <button type="button" class="decrement-qty bg-gray-200 text-gray-600 h-7 w-7 rounded-l-md flex items-center justify-center">-</button>
                                                                    <input type="number" name="items[{{ $service->id }}][{{ $item->id }}]" value="0" min="0" class="item-qty h-7 w-12 text-center border-y border-gray-200 focus:outline-none text-sm">
                                                                    <button type="button" class="increment-qty bg-gray-200 text-gray-600 h-7 w-7 rounded-r-md flex items-center justify-center">+</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-gray-500 italic">Aucune décoration disponible.</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Autres catégories de services, comme Animation, Salle, etc. -->
                        <!-- ... -->
                    </div>
                </div>

                <!-- Notes et instructions spéciales -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                    <h2 class="text-xl font-semibold text-[#333333] mb-4">Notes et instructions spéciales</h2>
                    <textarea name="notes" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]" placeholder="Ajoutez des notes ou instructions spéciales pour votre réservation..."></textarea>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-4 mb-8">
                    <a href="{{ route('planning.index') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition duration-300 flex items-center">
                        Annuler
                    </a>
                    <button type="submit" class="px-6 py-3 bg-[#FADADD] hover:bg-[#C08081] text-[#333333] hover:text-white rounded-lg transition duration-300 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Confirmer la réservation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Prix des items indexés par ID
    const itemPrices = {
        @foreach($traiteur->services as $service)
            @foreach($service->menuItems ?? [] as $item)
                {{ $item->id }}: {{ $item->price }},
            @endforeach
            @foreach($service->decorations ?? [] as $item)
                {{ $item->id }}: {{ $item->price }},
            @endforeach
            // Ajoutez d'autres types d'items ici
        @endforeach
    };

    // Fonction pour calculer le total
    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.item-qty').forEach(input => {
            const serviceId = input.name.match(/items\[(\d+)\]/)[1];
            const itemId = input.name.match(/\[(\d+)\]$/)[1];
            const quantity = parseInt(input.value) || 0;
            const price = itemPrices[itemId] || 0;

            total += price * quantity;
        });

        document.getElementById('totalBudget').textContent = total.toFixed(2) + ' DH';
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Afficher/masquer les items de service quand on coche un service
        document.querySelectorAll('.service-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const itemsContainer = this.closest('div').nextElementSibling;
                if (this.checked) {
                    itemsContainer.classList.remove('hidden');
                } else {
                    itemsContainer.classList.add('hidden');
                    // Remettre toutes les quantités à zéro
                    itemsContainer.querySelectorAll('.item-qty').forEach(input => {
                        input.value = 0;
                    });
                    calculateTotal();
                }
            });
        });

        // Gestion des boutons +/- pour les quantités
        document.querySelectorAll('.increment-qty').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.previousElementSibling;
                input.value = parseInt(input.value) + 1;
                calculateTotal();
            });
        });

        document.querySelectorAll('.decrement-qty').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.nextElementSibling;
                input.value = Math.max(0, parseInt(input.value) - 1);
                calculateTotal();
            });
        });

        // Mettre à jour le total quand les quantités changent
        document.querySelectorAll('.item-qty').forEach(input => {
            input.addEventListener('change', calculateTotal);
        });

        // Validation du formulaire
        document.getElementById('reservationForm').addEventListener('submit', function(e) {
            let hasSelectedItems = false;

            // Vérifier si au moins un service est sélectionné
            const serviceCheckboxes = document.querySelectorAll('.service-checkbox:checked');
            if (serviceCheckboxes.length === 0) {
                alert('Veuillez sélectionner au moins un service.');
                e.preventDefault();
                return;
            }

            // Vérifier si au moins un item a une quantité > 0
            document.querySelectorAll('.item-qty').forEach(input => {
                if (parseInt(input.value) > 0) {
                    hasSelectedItems = true;
                }
            });

            if (!hasSelectedItems) {
                alert('Veuillez sélectionner au moins un item avec une quantité supérieure à zéro.');
                e.preventDefault();
                return;
            }
        });
    });
</script>
@endsection
