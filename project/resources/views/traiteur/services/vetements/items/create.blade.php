@extends('layouts.app')

@section('title', 'Ajouter un vêtement')

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center mb-6">
                <a href="{{ route('traiteur.services.vetements.' . ($style == 'traditionnel' ? 'traditional' : 'modern')) }}" class="mr-4 text-gray-600 hover:text-[#C08081]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-[#333333] font-display">Ajouter un vêtement {{ $style == 'traditionnel' ? 'traditionnel' : 'moderne' }}</h1>
            </div>

            <div class="bg-white rounded-xl shadow-md p-8">
                <form action="{{ route('traiteur.services.vetements.items.store', $style) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="style" value="{{ $style }}">

                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-[#333333] mb-2">
                            Nom du vêtement <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]"
                            placeholder="Ex: Caftan brodé">

                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="price" class="block text-sm font-medium text-[#333333] mb-2">
                            Prix (DH) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="price" id="price" value="{{ old('price') }}" required min="0" step="0.01"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]"
                            placeholder="Ex: 1500.00">

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
                            <option value="" disabled {{ old('category') ? '' : 'selected' }}>Sélectionnez une catégorie</option>
                            <option value="robe_mariee" {{ old('category') == 'robe_mariee' ? 'selected' : '' }}>Femme</option>
                            <option value="costume_homme" {{ old('category') == 'costume_homme' ? 'selected' : '' }}>Homme</option>
                        </select>

                        @error('category')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-[#333333] mb-2">
                            Photo du vêtement
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4h-12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="photo" class="relative cursor-pointer bg-white rounded-md font-medium text-[#C08081] hover:text-[#FADADD] focus-within:outline-none">
                                        <span>Télécharger une image</span>
                                        <input id="photo" name="photo" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">ou glisser-déposer</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF jusqu'à 2MB</p>
                            </div>
                        </div>

                        @error('photo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-[#333333] mb-2">
                            Description (optionnelle)
                        </label>
                        <textarea name="description" id="description" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]"
                            placeholder="Décrivez ce vêtement...">{{ old('description') }}</textarea>

                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-[#FADADD] hover:bg-[#C08081] text-[#333333] hover:text-white rounded-lg transition duration-300 shadow-md">
                            Ajouter le vêtement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
