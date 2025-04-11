@extends('layouts.app')

@section('title', 'Détails du menu')

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <!-- En-tête avec flèche de retour et titre centré -->
            <div class="mb-8 relative">
                <a href="{{ route('traiteur.services.menu.index') }}" class="absolute left-0 top-1/2 -translate-y-1/2 text-gray-600 hover:text-[#C08081]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div class="text-center">
                    <h1 class="text-3xl font-bold text-[#333333] font-display">{{ $menu->title }}</h1>
                    <p class="text-gray-600 mt-2">{{ $menu->description ?? '' }}</p>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-md">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Section principale avec colonnes inversées -->
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Partie gauche : Consultation et filtres (maintenant plus large) -->
                <div class="w-full lg:w-2/3">
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-semibold text-[#333333] mb-6 text-center">Items du menu</h2>

                        <!-- Filtres par catégorie (plus grands et mieux alignés) -->
                        <div class="mb-6 border-b pb-4">
                            <div class="flex flex-wrap justify-center gap-4">
                                <button type="button"
                                    class="category-filter px-6 py-3 bg-[#FADADD] text-[#333333] rounded-lg shadow-sm font-medium text-base active"
                                    data-category="boisson">
                                    Boissons
                                </button>
                                <button type="button"
                                    class="category-filter px-6 py-3 bg-white hover:bg-[#FADADD]/20 text-gray-700 rounded-lg shadow-sm font-medium text-base"
                                    data-category="entree">
                                    Entrées
                                </button>
                                <button type="button"
                                    class="category-filter px-6 py-3 bg-white hover:bg-[#FADADD]/20 text-gray-700 rounded-lg shadow-sm font-medium text-base"
                                    data-category="plat">
                                    Plats
                                </button>
                                <button type="button"
                                    class="category-filter px-6 py-3 bg-white hover:bg-[#FADADD]/20 text-gray-700 rounded-lg shadow-sm font-medium text-base"
                                    data-category="dessert">
                                    Desserts
                                </button>
                            </div>
                        </div>

                        <!-- Affichage des items -->
                        @if(count($menu->menuItems) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="menu-items-container">
                                @foreach($menu->menuItems as $item)
                                    <div class="bg-white border rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300 menu-item" data-category="{{ $item->category }}">
                                        <div class="flex flex-col h-full">
                                            @if($item->photo)
                                                <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->name }}" class="w-full h-32 object-cover">
                                            @else
                                                <div class="w-full h-32 bg-gray-200 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="p-3 flex-grow">
                                                <div class="flex justify-between items-start mb-1">
                                                    <h3 class="text-base font-semibold text-[#333333]">{{ $item->name }}</h3>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                        {{ $item->category === 'boisson' ? 'bg-blue-100 text-blue-800' :
                                                           ($item->category === 'entree' ? 'bg-green-100 text-green-800' :
                                                           ($item->category === 'plat' ? 'bg-purple-100 text-purple-800' :
                                                           'bg-yellow-100 text-yellow-800')) }}">
                                                        {{ $item->category === 'boisson' ? 'Boisson' :
                                                           ($item->category === 'entree' ? 'Entrée' :
                                                           ($item->category === 'plat' ? 'Plat' : 'Dessert')) }}
                                                    </span>
                                                </div>
                                                <p class="text-gray-600 mb-3 text-sm line-clamp-2">{{ $item->description ?? 'Aucune description' }}</p>
                                                <div class="flex justify-between items-center mt-auto">
                                                    <span class="text-base font-semibold text-[#C08081]">{{ number_format($item->price, 2) }} DH</span>
                                                    <div class="flex space-x-1">
                                                        <a href="{{ route('traiteur.services.menu.items.edit', [$menu->id, $item->id]) }}" class="p-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-full transition duration-300" title="Modifier">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </a>
                                                        <form action="{{ route('traiteur.services.menu.items.destroy', [$menu->id, $item->id]) }}" method="POST" class="inline delete-item-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="p-1.5 bg-red-100 hover:bg-red-200 text-red-700 rounded-full transition duration-300 delete-item-btn" title="Supprimer" data-item-id="{{ $item->id }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Message catégorie vide -->
                            <div id="empty-category-message" class="hidden bg-gray-50 rounded-lg p-6 text-center mt-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h3 class="text-lg font-medium text-gray-600 mb-2">Aucun item dans cette catégorie</h3>
                                <p class="text-gray-500">Ajoutez des items à cette catégorie en utilisant le formulaire à droite.</p>
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-lg p-8 text-center" id="no-items-message">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h3 class="text-lg font-medium text-gray-600 mb-2">Ce menu ne contient aucun item</h3>
                                <p class="text-gray-500 mb-2">Ajoutez vos premiers items à l'aide du formulaire à droite.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Partie droite : Formulaire d'ajout d'un item -->
                <div class="w-full lg:w-1/3">
                    <div class="bg-white rounded-xl shadow-md p-6 sticky top-6">
                        <h2 class="text-xl font-semibold text-[#333333] mb-4">Ajouter un item</h2>

                        <form action="{{ route('traiteur.services.menu.items.store', $menu->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf

                            <div>
                                <label for="name" class="block text-sm font-medium text-[#333333] mb-1">
                                    Nom <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]"
                                    placeholder="Ex: Couscous Royal">

                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="price" class="block text-sm font-medium text-[#333333] mb-1">
                                    Prix (DH) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="price" id="price" required min="0" step="0.01"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]"
                                    placeholder="Ex: 150.00">

                                @error('price')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="category" class="block text-sm font-medium text-[#333333] mb-1">
                                    Catégorie <span class="text-red-500">*</span>
                                </label>
                                <select name="category" id="category" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]">
                                    <option value="" disabled selected>Sélectionnez une catégorie</option>
                                    <option value="boisson">Boissons</option>
                                    <option value="entree">Entrées</option>
                                    <option value="plat">Plats</option>
                                    <option value="dessert">Desserts</option>
                                </select>

                                @error('category')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-[#333333] mb-1">
                                    Image
                                </label>
                                <div class="mt-1">
                                    <label for="photo" class="cursor-pointer flex items-center justify-center w-full px-4 py-2 rounded border border-gray-300 hover:border-[#FADADD] bg-white text-gray-700 hover:bg-[#FADADD]/5 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Sélectionner une image
                                    </label>
                                    <input id="photo" name="photo" type="file" class="hidden" accept="image/*" onchange="updateFileName(this)">
                                    <p id="file-name" class="mt-1 text-xs text-gray-500 truncate">Aucun fichier sélectionné</p>
                                </div>

                                @error('photo')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-[#333333] mb-1">
                                    Description
                                </label>
                                <textarea name="description" id="description" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]"
                                    placeholder="Décrivez cet item..."></textarea>

                                @error('description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="w-full px-4 py-2 bg-[#FADADD] hover:bg-[#C08081] text-[#333333] hover:text-white rounded-lg transition duration-300 shadow-md flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Ajouter l'item
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modale de confirmation de suppression -->
    <div id="delete-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 overflow-hidden transform transition-all">
            <div class="px-6 py-4 bg-red-50 border-b border-red-100">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-100 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-red-800">Confirmer la suppression</h3>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4">
                <p class="text-gray-700">Êtes-vous sûr de vouloir supprimer cet item ? Cette action est irréversible.</p>
            </div>
            <div class="px-6 py-3 bg-gray-50 flex justify-end space-x-3">
                <button id="cancel-delete" type="button" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    Annuler
                </button>
                <button id="confirm-delete" type="button" class="px-4 py-2 bg-red-600 rounded-lg text-white font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    Supprimer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Affichage du nom du fichier sélectionné
    function updateFileName(input) {
        const fileName = input.files[0] ? input.files[0].name : 'Aucun fichier sélectionné';
        document.getElementById('file-name').textContent = fileName;
    }

    // Filtrage par catégorie
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.category-filter');
        const menuItems = document.querySelectorAll('.menu-item');
        const emptyMessage = document.getElementById('empty-category-message');

        // Fonction pour filtrer les items
        function filterItems(category) {
            let visibleCount = 0;

            menuItems.forEach(item => {
                const itemCategory = item.getAttribute('data-category');

                if (category === itemCategory) {
                    item.classList.remove('hidden');
                    visibleCount++;
                } else {
                    item.classList.add('hidden');
                }
            });

            // Afficher ou masquer le message "aucun item dans cette catégorie"
            if (visibleCount === 0 && menuItems.length > 0) {
                emptyMessage.classList.remove('hidden');
            } else {
                emptyMessage.classList.add('hidden');
            }
        }

        // Initialiser avec la première catégorie (boisson)
        filterItems('boisson');

        // Ajouter les événements de clic pour les boutons de filtre
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Mise à jour des classes des boutons
                filterButtons.forEach(btn => {
                    btn.classList.remove('bg-[#FADADD]', 'text-[#333333]', 'active');
                    btn.classList.add('bg-white', 'hover:bg-[#FADADD]/20', 'text-gray-700');
                });

                this.classList.remove('bg-white', 'hover:bg-[#FADADD]/20', 'text-gray-700');
                this.classList.add('bg-[#FADADD]', 'text-[#333333]', 'active');

                // Filtrer les items par catégorie
                const selectedCategory = this.getAttribute('data-category');
                filterItems(selectedCategory);
            });
        });

        // Gestion de la modale de suppression
        const deleteModal = document.getElementById('delete-modal');
        const cancelDelete = document.getElementById('cancel-delete');
        const confirmDelete = document.getElementById('confirm-delete');
        let currentForm = null;

        // Ouvrir la modale lors du clic sur un bouton de suppression
        document.querySelectorAll('.delete-item-btn').forEach(button => {
            button.addEventListener('click', function() {
                currentForm = this.closest('.delete-item-form');
                deleteModal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden'); // Empêcher le défilement
            });
        });

        // Fermer la modale si on clique sur Annuler
        cancelDelete.addEventListener('click', function() {
            deleteModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            currentForm = null;
        });

        // Confirmer la suppression
        confirmDelete.addEventListener('click', function() {
            if (currentForm) {
                currentForm.submit();
            }
            deleteModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });

        // Fermer la modale si on clique en dehors
        deleteModal.addEventListener('click', function(e) {
            if (e.target === deleteModal) {
                deleteModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                currentForm = null;
            }
        });
    });
</script>
@endsection
