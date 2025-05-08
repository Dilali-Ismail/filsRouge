@extends('layouts.app')

@section('title', 'Modifier une négafa')

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center mb-6">
                <a href="{{ route('traiteur.services.negafa.index') }}" class="mr-4 text-gray-600 hover:text-[#C08081]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-[#333333] font-display">Modifier une négafa</h1>
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

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-md">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-md p-8">
                <form action="{{ route('traiteur.services.negafa.update', $negafa->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nom de la négafa -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-[#333333] mb-2">
                                Nom <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $negafa->name) }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]"
                                placeholder="Nom de la négafa">

                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Prix -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-[#333333] mb-2">
                                Prix (DH) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="price" id="price" value="{{ old('price', $negafa->price) }}" required min="0" step="0.01"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]"
                                placeholder="Ex: 2500.00">

                            @error('price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Expérience -->
                        <div>
                            <label for="experience" class="block text-sm font-medium text-[#333333] mb-2">
                                Expérience
                            </label>
                            <input type="text" name="experience" id="experience" value="{{ old('experience', $negafa->experience) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]"
                                placeholder="Ex: 5 ans">

                            @error('experience')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Photo de la négafa -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-[#333333] mb-2">
                                Photo
                            </label>

                            @if($negafa->photo)
                                <div class="mb-3">
                                    <p class="text-sm text-gray-500 mb-2">Image actuelle :</p>
                                    <div class="relative inline-block">
                                        <img src="{{ asset('storage/' . $negafa->photo) }}" alt="{{ $negafa->name }}" class="h-40 w-auto rounded-md border border-gray-200">
                                        <div class="absolute top-0 right-0 mt-2 mr-2">
                                            <label class="cursor-pointer bg-white rounded-full p-1 shadow-md hover:bg-gray-100 transition-colors" title="Supprimer l'image">
                                                <input type="checkbox" name="remove_photo" id="remove_photo" value="1" class="hidden">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" onclick="togglePhotoRemoval()">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </label>
                                        </div>
                                    </div>
                                    <p id="remove_photo_text" class="text-xs text-red-500 hidden mt-1">L'image sera supprimée lors de la sauvegarde.</p>
                                </div>
                            @endif

                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4h-12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="photo" class="relative cursor-pointer bg-white rounded-md font-medium text-[#C08081] hover:text-[#FADADD] focus-within:outline-none">
                                            <span>{{ $negafa->photo ? 'Changer l\'image' : 'Télécharger une image' }}</span>
                                            <input id="photo" name="photo" type="file" class="sr-only" accept="image/*" onchange="showPreview(this)">
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF jusqu'à 2MB</p>
                                </div>
                            </div>
                            <div id="image-preview" class="mt-3 hidden">
                                <p class="text-sm text-gray-500 mb-1">Aperçu de la nouvelle image :</p>
                                <img id="preview-image" src="#" alt="Aperçu de l'image" class="max-h-40 rounded-md">
                            </div>

                            @error('photo')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-[#333333] mb-2">
                                Description
                            </label>
                            <textarea name="description" id="description" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]"
                                placeholder="Décrivez cette négafa, ses compétences et services...">{{ old('description', $negafa->description) }}</textarea>

                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-between mt-8">
                        <a href="{{ route('traiteur.services.negafa.show', $negafa->id) }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition duration-300 shadow-md">
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
    function showPreview(input) {
        const preview = document.getElementById('image-preview');
        const image = document.getElementById('preview-image');
        const removePhotoCheckbox = document.getElementById('remove_photo');
        const removePhotoText = document.getElementById('remove_photo_text');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function (e) {
                image.src = e.target.result;
                preview.classList.remove('hidden');

                // Si une nouvelle image est sélectionnée, désactiver l'option de suppression
                if (removePhotoCheckbox) {
                    removePhotoCheckbox.checked = false;
                    removePhotoText.classList.add('hidden');
                }
            };

            reader.readAsDataURL(input.files[0]);
        } else {
            preview.classList.add('hidden');
        }
    }

    function togglePhotoRemoval() {
        const checkbox = document.getElementById('remove_photo');
        const text = document.getElementById('remove_photo_text');

        checkbox.checked = !checkbox.checked;

        if (checkbox.checked) {
            text.classList.remove('hidden');
        } else {
            text.classList.add('hidden');
        }
    }
</script>
@endsection
