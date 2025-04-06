@extends('layouts.app')

@section('title', 'Messagerie')

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto bg-white rounded-xl shadow-lg p-6">
            <h1 class="text-3xl font-bold text-[#333333] mb-6 font-display">Messagerie</h1>

            <div class="flex flex-col md:flex-row h-[600px]">
                <!-- Liste des contacts -->
                <div class="w-full md:w-1/3 border-r border-gray-200 pr-4 overflow-y-auto">
                    <h2 class="text-xl font-semibold text-[#333333] mb-4">
                        @if(Auth::user()->isMariee())
                            Mes traiteurs
                        @else
                            Mes clients
                        @endif
                    </h2>

                    <!-- Liste des contacts vide pour l'instant -->
                    <p class="text-gray-500">
                        @if(Auth::user()->isMariee())
                            Vous n'avez pas encore contacté de traiteurs.
                        @else
                            Vous n'avez pas encore de messages de clients.
                        @endif
                    </p>
                </div>

                <!-- Zone de conversation -->
                <div class="w-full md:w-2/3 pl-4 flex flex-col">
                    <div class="flex-grow flex items-center justify-center bg-gray-50 rounded-lg">
                        <p class="text-gray-500">Sélectionnez une conversation pour commencer à discuter.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
