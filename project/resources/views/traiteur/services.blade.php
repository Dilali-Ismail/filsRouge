@extends('layouts.app')

@section('title', 'Gérer mes services')

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-3xl font-bold text-[#333333] mb-6 font-display">Gérer mes services</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-[#333333]">Mes services</h2>
                    <a href="#" class="px-4 py-2 bg-[#FADADD] hover:bg-[#C08081] text-[#333333] hover:text-white rounded-lg transition duration-300">
                        Ajouter un service
                    </a>
                </div>

                <p class="text-gray-600">Vous n'avez pas encore ajouté de services.</p>
            </div>
        </div>
    </div>
</div>
@endsection
