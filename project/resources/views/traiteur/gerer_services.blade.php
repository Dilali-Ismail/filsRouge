@extends('layouts.app')

@section('title', 'Gérer mes services')

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-[#333333] mb-6 font-display">Gérer mes services</h1>

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Menu de navigation latéral -->
            <div class="md:w-1/4 bg-white rounded-xl shadow-md p-4">
                <h2 class="text-xl font-semibold text-[#333333] mb-4">Catégories de services</h2>
                <nav class="space-y-1">
                    @foreach($categories as $category)
                    <a href="{{

                         $category->name == 'menu' ? route('traiteur.services.menu.index') :
                        ($category->name == 'vetements' ? route('traiteur.services.vetements.index') :
                        ($category->name == 'negafa' ? route('traiteur.services.negafa.index') : '#'))

                    }}"
                    class="block px-4 py-2 rounded-lg transition-colors duration-200
                            {{ $activeTab == $category->name ? 'bg-[#FADADD] text-[#333333]' : 'text-gray-600 hover:bg-gray-100' }}"
                    data-category="{{ $category->name }}">
                        {{ ucfirst($category->name) }}
                    </a>
                    @endforeach
                </nav>
            </div>

            <!-- Contenu principal qui change selon la catégorie sélectionnée -->
            <div class="md:w-3/4 bg-white rounded-xl shadow-md p-6" id="service-content">
                {!! $contentView !!}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du clic sur les liens de catégories
    const categoryLinks = document.querySelectorAll('nav a[data-category]');
    const contentContainer = document.getElementById('service-content');

    categoryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Si le lien est # (catégorie non implémentée), ne pas faire de requête AJAX
            if (this.getAttribute('href') === '#') {
                e.preventDefault();

                // Mise à jour de la classe active
                categoryLinks.forEach(l => l.classList.remove('bg-[#FADADD]', 'text-[#333333]'));
                categoryLinks.forEach(l => l.classList.add('text-gray-600', 'hover:bg-gray-100'));
                this.classList.remove('text-gray-600', 'hover:bg-gray-100');
                this.classList.add('bg-[#FADADD]', 'text-[#333333]');

                // Afficher un message temporaire
                contentContainer.innerHTML = `
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-600 mb-2">Fonctionnalité à venir</h3>
                        <p class="text-gray-500 mb-6">La gestion des ${this.getAttribute('data-category')} sera bientôt disponible.</p>
                    </div>
                `;

                return;
            }

            e.preventDefault();

            // Mise à jour de la classe active
            categoryLinks.forEach(l => l.classList.remove('bg-[#FADADD]', 'text-[#333333]'));
            categoryLinks.forEach(l => l.classList.add('text-gray-600', 'hover:bg-gray-100'));
            this.classList.remove('text-gray-600', 'hover:bg-gray-100');
            this.classList.add('bg-[#FADADD]', 'text-[#333333]');

            // Récupération du contenu via AJAX
            fetch(this.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                contentContainer.innerHTML = html;

                // Mise à jour de l'URL sans rechargement de la page
                history.pushState({}, '', this.href);
            })
            .catch(error => console.error('Erreur:', error));
        });
    });
});
</script>
@endpush
@endsection
