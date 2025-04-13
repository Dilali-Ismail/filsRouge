@extends('layouts.app')

@section('title', $photographer->name . ' - Portfolio')

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12">
    <div class="container mx-auto px-4">
        <!-- En-tête avec informations du photographe -->
        <div class="flex items-center mb-4">
            <a href="{{ route('traiteur.services.photographer.index') }}" class="mr-4 text-gray-600 hover:text-[#C08081]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-[#333333] font-display">Portfolio de {{ $photographer->name }}</h1>
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

        <!-- Profil du photographe -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="md:w-1/4">
                    @if($photographer->photo)
                        <img src="{{ asset('storage/' . $photographer->photo) }}" alt="{{ $photographer->name }}" class="w-full h-48 md:h-64 object-cover rounded-lg">
                    @else
                        <div class="w-full h-48 md:h-64 bg-gray-200 flex items-center justify-center rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="md:w-3/4">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="text-2xl font-semibold text-[#333333]">{{ $photographer->name }}</h2>
                            @if($photographer->experience)
                                <span class="inline-block mt-1 bg-gray-100 px-3 py-1 rounded-full text-sm text-gray-700">{{ $photographer->experience }} d'expérience</span>
                            @endif
                        </div>
                        <span class="bg-[#FADADD] px-4 py-2 rounded-lg text-[#333333] font-semibold">{{ number_format($photographer->price, 2) }} DH</span>
                    </div>
                    <p class="text-gray-700 mb-4">{{ $photographer->description ?? 'Aucune description disponible.' }}</p>

                    <div class="flex gap-2">
                        <a href="{{ route('traiteur.services.photographer.edit', $photographer->id) }}" class="px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition duration-300 inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Modifier
                        </a>
                        <form action="{{ route('traiteur.services.photographer.destroy', $photographer->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg transition duration-300 inline-flex items-center" onclick="confirmDelete(this)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sections du portfolio -->
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Section gauche : Formulaire d'ajout -->
            <div class="lg:w-1/3">
                <div class="bg-white rounded-xl shadow-md p-6 sticky top-6">
                    <h2 class="text-xl font-semibold text-[#333333] mb-4">Ajouter au portfolio</h2>

                    <form action="{{ route('traiteur.services.photographer.portfolio.store', $photographer->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div>
                            <label for="type" class="block text-sm font-medium text-[#333333] mb-2">
                                Type de média <span class="text-red-500">*</span>
                            </label>
                            <div class="flex gap-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="type" value="image" class="text-[#FADADD] focus:ring-[#FADADD]" checked>
                                    <span class="ml-2">Image</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="type" value="video" class="text-[#FADADD] focus:ring-[#FADADD]">
                                    <span class="ml-2">Vidéo</span>
                                </label>
                            </div>

                            @error('type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="title" class="block text-sm font-medium text-[#333333] mb-2">
                                Titre
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]"
                                   placeholder="Titre du média">

                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-[#333333] mb-2">
                                Description
                            </label>
                            <textarea name="description" id="description" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]"
                                      placeholder="Description du média">{{ old('description') }}</textarea>

                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#333333] mb-2">
                                Fichier <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <label for="file" class="cursor-pointer flex items-center justify-center w-full px-4 py-2 rounded border border-gray-300 hover:border-[#FADADD] bg-white text-gray-700 hover:bg-[#FADADD]/5 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    Sélectionner un fichier
                                </label>
                                <input id="file" name="file" type="file" class="hidden" accept="image/*,video/*" onchange="updateFileName(this)">
                                <p id="file-name" class="mt-1 text-xs text-gray-500 truncate">Aucun fichier sélectionné</p>
                            </div>

                            @error('file')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full px-4 py-2 bg-[#FADADD] hover:bg-[#C08081] text-[#333333] hover:text-white rounded-lg transition duration-300 shadow-md flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Ajouter au portfolio
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Section droite : Affichage du portfolio -->
            <div class="lg:w-2/3">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-[#333333]">Portfolio</h2>

                        <!-- Filtres -->
                        <div class="bg-gray-100 p-1 rounded-lg inline-flex">
                            <button type="button" class="filter-btn px-4 py-2 rounded-md text-sm font-medium bg-[#FADADD] text-[#333333] focus:outline-none" data-filter="all">
                                Tous
                            </button>
                            <button type="button" class="filter-btn px-4 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none" data-filter="image">
                                Images
                            </button>
                            <button type="button" class="filter-btn px-4 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none" data-filter="video">
                                Vidéos
                            </button>
                        </div>
                    </div>

                    @if(count($photographer->portfolioItems) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="portfolio-items">
                            @foreach($photographer->portfolioItems as $item)
                                <div class="portfolio-item bg-white border rounded-lg shadow-sm overflow-hidden" data-type="{{ $item->type }}">
                                    <div class="relative">
                                        @if($item->type == 'image')
                                            <img src="{{ asset('storage/' . $item->file_path) }}" alt="{{ $item->title ?? 'Image' }}" class="w-full h-48 object-cover">
                                        @else
                                            <video class="w-full h-48 object-cover" controls>
                                                <source src="{{ asset('storage/' . $item->file_path) }}" type="video/mp4">
                                                Votre navigateur ne supporte pas la lecture de vidéos.
                                            </video>
                                        @endif

                                        <div class="absolute top-2 right-2 flex space-x-1">
                                            <a href="#" class="p-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-full transition duration-300" title="Modifier">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('traiteur.services.photographer.portfolio.destroy', [$photographer->id, $item->id]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="p-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-full transition duration-300" title="Supprimer" onclick="confirmDeleteItem(this)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>

                                        <div class="absolute bottom-0 left-0 bg-gradient-to-t from-black/60 to-transparent w-full pt-8 pb-2 px-3">
                                            <span class="inline-block px-2 py-1 text-xs rounded-full
                                                {{ $item->type == 'image' ? 'bg-blue-500/70 text-white' : 'bg-red-500/70 text-white' }}">
                                                {{ $item->type == 'image' ? 'Image' : 'Vidéo' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="p-4">
                                        <h3 class="font-medium text-[#333333] mb-1">{{ $item->title ?? 'Sans titre' }}</h3>
                                        <p class="text-sm text-gray-600 line-clamp-2">{{ $item->description ?? 'Aucune description' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-lg p-8 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-600 mb-2">Aucun élément dans le portfolio</h3>
                            <p class="text-gray-500">Commencez à ajouter des images et des vidéos à l'aide du formulaire.</p>
                        </div>
                    @endif
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
            <p class="text-gray-700" id="delete-message">Êtes-vous sûr de vouloir supprimer cet élément ? Cette action est irréversible.</p>
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

<script>
    let currentForm = null;
    let deleteType = '';

    // Mise à jour du nom du fichier
    function updateFileName(input) {
        const fileName = input.files[0] ? input.files[0].name : 'Aucun fichier sélectionné';
        document.getElementById('file-name').textContent = fileName;
    }

    // Confirmation de suppression du photographe
    function confirmDelete(button) {
        currentForm = button.closest('form');
        deleteType = 'photographer';
        document.getElementById('delete-message').textContent = 'Êtes-vous sûr de vouloir supprimer ce photographe ? Cette action est irréversible et supprimera aussi tout son portfolio.';
        document.getElementById('delete-modal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    // Confirmation de suppression d'un élément du portfolio
    function confirmDeleteItem(button) {
        currentForm = button.closest('form');
        deleteType = 'item';
        document.getElementById('delete-message').textContent = 'Êtes-vous sûr de vouloir supprimer cet élément du portfolio ? Cette action est irréversible.';
        document.getElementById('delete-modal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('delete-modal');
        const cancelDelete = document.getElementById('cancel-delete');
        const confirmDelete = document.getElementById('confirm-delete');
        const filterButtons = document.querySelectorAll('.filter-btn');
        const portfolioItems = document.querySelectorAll('.portfolio-item');

        // Gestion des filtres du portfolio
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');

                // Mise à jour des styles des boutons
                filterButtons.forEach(btn => {
                    btn.classList.remove('bg-[#FADADD]', 'text-[#333333]');
                    btn.classList.add('text-gray-700', 'hover:bg-gray-200');
                });
                this.classList.remove('text-gray-700', 'hover:bg-gray-200');
                this.classList.add('bg-[#FADADD]', 'text-[#333333]');

                // Filtrage des éléments
                portfolioItems.forEach(item => {
                    if (filter === 'all' || item.getAttribute('data-type') === filter) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });
            });
        });

        // Gestion de la modale de suppression
        cancelDelete.addEventListener('click', function() {
            deleteModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            currentForm = null;
        });

        confirmDelete.addEventListener('click', function() {
            if (currentForm) {
                currentForm.submit();
            }
            deleteModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });

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
