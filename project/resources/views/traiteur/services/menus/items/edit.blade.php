@extends('layouts.app')

@section('title', 'Modifier un item')

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center mb-6">
                <a href="{{ route('traiteur.services.menu.show', $menu->id) }}" class="mr-4 text-gray-600 hover:text-[#C08081]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-[#333333] font-display">Modifier un item du menu</h1>
            </div>

            <div class="bg-white rounded-xl shadow-md p-8">
                <form action="{{ route('traiteur.services.menu.items.update', [$menu->id, $menuItem->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-[#333333] mb-2">
                            Nom <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $menuItem->name) }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]"
                            placeholder="Ex: Couscous Royal">

                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="price" class="block text-sm font-medium text-[#333333] mb-2">
                            Prix (DH) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="price" id="price" value="{{ old('price', $menuItem->price) }}" required min="0" step="0.01"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]"
                            placeholder="Ex: 150.00">

                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="category" class="block text-sm font-medium text-[#333333] mb-2">
                            Catégorie <span class="text-red-500">*</span>
                        </label>
                        <select name="category" id="category" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]">
                            <option value="boisson" {{ old('category', $menuItem->category) === 'boisson' ? 'selected' : '' }}>Boissons</option>
                            <option value="entree" {{ old('category', $menuItem->category) === 'entree' ? 'selected' : '' }}>Entrées</option>
                            <option value="plat" {{ old('category', $menuItem->category) === 'plat' ? 'selected' : '' }}>Plats</option>
                            <option value="dessert" {{ old('category', $menuItem->category) === 'dessert' ? 'selected' : '' }}>Desserts</option>
                        </select>

                        @error('category')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-[#333333] mb-2">
                            Image
                        </label>
                        <div class="mt-1 flex items-center">
                            <div id="preview-container" class="{{ $menuItem->photo ? '' : 'hidden' }} w-32 h-32 rounded-lg border border-gray-300 flex items-center justify-center overflow-hidden mr-4 bg-gray-100">
                                <img id="preview-image" src="{{ $menuItem->photo ? asset('storage/' . $menuItem->photo) : '#' }}" alt="Aperçu" class="w-full h-full object-cover">
                            </div>
                            <label for="photo" class="cursor-pointer flex flex-col items-center justify-center w-32 h-32 rounded-lg border-2 border-dashed border-gray-300 hover:border-[#FADADD] bg-white hover:bg-[#FADADD]/5 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="mt-1 text-xs text-gray-500">{{ $menuItem->photo ? 'Changer l\'image' : 'Ajouter une image' }}</p>
                                </div>
                                <input id="photo" name="photo" type="file" class="hidden" accept="image/*" onchange="previewImage(this)"/>
                            </label>
                            <button type="button" id="remove-image" class="ml-3 text-gray-400 hover:text-red-500 {{ $menuItem->photo ? '' : 'hidden' }}" onclick="removeImage()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Laissez vide pour conserver l'image actuelle.</p>

                        @error('photo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-[#333333] mb-2">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]"
                            placeholder="Décrivez cet item...">{{ old('description', $menuItem->description) }}</textarea>

                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('traiteur.services.menu.show', $menu->id) }}"
                           class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition duration-300">
                            Annuler
                        </a>
                        <button type="submit" class="px-6 py-3 bg-[#FADADD] hover:bg-[#C08081] text-[#333333] hover:text-white rounded-lg transition duration-300 shadow-md">
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        const previewContainer = document.getElementById('preview-container');
        const previewImage = document.getElementById('preview-image');
        const removeButton = document.getElementById('remove-image');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.classList.remove('hidden');
                removeButton.classList.remove('hidden');
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImage() {
        const input = document.getElementById('photo');
        const previewContainer = document.getElementById('preview-container');
        const removeButton = document.getElementById('remove-image');

        // Ajouter un champ caché pour indiquer que l'image doit être supprimée
        if (!document.getElementById('remove_photo')) {
            const removeField = document.createElement('input');
            removeField.type = 'hidden';
            removeField.name = 'remove_photo';
            removeField.id = 'remove_photo';
            removeField.value = '1';
            input.parentNode.appendChild(removeField);
        }

        input.value = '';
        previewContainer.classList.add('hidden');
        removeButton.classList.add('hidden');
    }
</script>
@endsection
