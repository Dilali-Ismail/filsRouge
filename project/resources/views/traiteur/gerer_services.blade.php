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
                    <a href="{{ route('traiteur.services.' . $category->name . '.index') }}"
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
