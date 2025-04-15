<div class="mb-6">
    <h2 class="text-2xl font-semibold text-[#333333] mb-6 text-center">Gestion des Décorations</h2>

    <!-- Bouton d'ajout -->
    <div class="mb-6 flex justify-end">
        <a href="{{ route('traiteur.services.decoration.create') }}" class="px-4 py-2 bg-[#FADADD] hover:bg-[#C08081] text-[#333333] hover:text-white rounded-lg transition duration-300 shadow-md flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Ajouter une décoration
        </a>
    </div>

    <!-- Liste des décorations -->
    @if(count($decorations) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($decorations as $decoration)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 h-full flex flex-col">
                    <!-- Image de la décoration -->
                    <div class="relative h-48">
                        @if($decoration->photo)
                            <img src="{{ asset('storage/' . $decoration->photo) }}" alt="{{ $decoration->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                            </div>
                        @endif
                        <div class="absolute top-0 right-0 mt-2 mr-2 flex gap-1">
                            <a href="{{ route('traiteur.services.decoration.edit', $decoration->id) }}" class="p-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-full transition duration-300" title="Modifier">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('traiteur.services.decoration.destroy', $decoration->id) }}" method="POST" class="inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="p-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-full transition duration-300 delete-btn" title="Supprimer" onclick="confirmDelete(this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Informations sur la décoration -->
                    <div class="p-4 flex-grow">
                        <h3 class="text-lg font-semibold text-[#333333] mb-2">{{ $decoration->name }}</h3>
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $decoration->description ?? 'Aucune description' }}</p>

                        <div class="mt-auto">
                            <span class="text-[#C08081] font-semibold">{{ number_format($decoration->price, 2) }} DH</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl shadow-md p-8 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-600 mb-4">Aucune décoration disponible</h3>
            <p class="text-gray-500 mb-6">Ajoutez votre première décoration en utilisant le bouton "Ajouter une décoration".</p>
            <a href="{{ route('traiteur.services.decoration.create') }}" class="px-5 py-3 bg-[#FADADD] hover:bg-[#C08081] text-[#333333] hover:text-white rounded-lg transition duration-300 shadow-md inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Ajouter une décoration
            </a>
        </div>
    @endif
</div>
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
            <p class="text-gray-700" id="delete-message">Êtes-vous sûr de vouloir supprimer cette décoration ? Cette action est irréversible.</p>
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
    // Variable pour stocker le formulaire de suppression en cours
    let currentForm = null;

    // Fonction pour ouvrir la modale de confirmation
    function confirmDelete(button) {
        currentForm = button.closest('.delete-form');
        const modal = document.getElementById('delete-modal');

        // Affichage de la modale
        if (modal) {
            document.getElementById('delete-message').textContent = 'Êtes-vous sûr de vouloir supprimer cette décoration ? Cette action est irréversible.';
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    // Initialisation des événements une fois que le DOM est chargé
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('delete-modal');
        const cancelDelete = document.getElementById('cancel-delete');
        const confirmDelete = document.getElementById('confirm-delete');

        // Si l'un des éléments n'existe pas, on sort
        if (!deleteModal || !cancelDelete || !confirmDelete) return;

        // Fermer la modale sur Annuler
        cancelDelete.addEventListener('click', function() {
            deleteModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            currentForm = null;
        });

        // Soumettre le formulaire sur Confirmer
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
