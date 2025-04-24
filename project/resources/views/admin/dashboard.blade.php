@extends('layouts.app')

@section('title', 'Tableau de bord administrateur')

@section('styles')
<style>
    /* Styles généraux */
    .soft-border {
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        overflow: hidden;
    }
    .section-header {
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 0.75rem;
        margin-bottom: 1rem;
    }
    .traiteur-table th {
        position: sticky;
        top: 0;
        background-color: white;
        z-index: 10;
    }
    .table-container {
        height: 520px;
        overflow-y: auto;
        border-radius: 0.75rem;
    }
    .admin-card {
        transition: all 0.3s ease;
    }
    .admin-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    .status-badge.verified {
        background-color: #D1FAE5;
        color: #065F46;
    }
    .status-badge.pending {
        background-color: #FEF3C7;
        color: #92400E;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-[#333333] font-display">Tableau de bord administrateur</h1>
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

            <!-- Cartes de statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Carte nombre de traiteurs -->
                <div class="bg-white rounded-xl shadow-md p-6 soft-border admin-card">
                    <div class="flex items-center">
                        <div class="bg-[#FADADD]/20 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#C08081]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Nombre total de traiteurs</p>
                            <h2 class="text-3xl font-bold text-[#333333]">{{ $traiteurCount }}</h2>
                        </div>
                    </div>
                </div>

                <!-- Carte nombre de mariées -->
                <div class="bg-white rounded-xl shadow-md p-6 soft-border admin-card">
                    <div class="flex items-center">
                        <div class="bg-indigo-100 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Nombre total de mariées</p>
                            <h2 class="text-3xl font-bold text-[#333333]">{{ $marieeCount }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau des traiteurs -->
            <div class="bg-white rounded-xl shadow-md p-6 soft-border">
                <h2 class="text-xl font-semibold text-[#333333] mb-4 section-header">Liste des traiteurs</h2>

                <div class="table-container">
                    <table class="min-w-full traiteur-table">
                        <thead>
                            <tr class="border-b">
                                <th class="py-3 px-4 text-left">Nom du responsable</th>
                                <th class="py-3 px-4 text-left">Email</th>
                                <th class="py-3 px-4 text-left">Téléphone</th>
                                <th class="py-3 px-4 text-left">Ville</th>
                                <th class="py-3 px-4 text-left">Statut</th>
                                <th class="py-3 px-4 text-left">Inscription</th>
                                <th class="py-3 px-4 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($traiteurs as $traiteur)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-4 px-4">
                                        {{ $traiteur->manager_name }}
                                    </td>
                                    <td class="py-4 px-4">
                                        {{ $traiteur->user->email }}
                                    </td>
                                    <td class="py-4 px-4">
                                        {{ $traiteur->phone_number }}
                                    </td>
                                    <td class="py-4 px-4">
                                        {{ $traiteur->city }}
                                    </td>
                                    <td class="py-4 px-4">
                                        @if($traiteur->is_verified)
                                            <span class="status-badge verified">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Vérifié
                                            </span>
                                        @else
                                            <span class="status-badge pending">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                En attente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4">
                                        {{ $traiteur->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="flex space-x-2">
                                            @if(!$traiteur->is_verified)
                                                <form action="{{ route('admin.traiteurs.verify', $traiteur->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm inline-flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        Vérifier
                                                    </button>
                                                </form>

                                                <form action="{{ route('admin.traiteurs.reject', $traiteur->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir rejeter et supprimer ce traiteur?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm inline-flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        Rejeter
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-sm text-gray-500">Déjà vérifié</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-4 px-4 text-center text-gray-500">
                                        Aucun traiteur enregistré pour le moment.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $traiteurs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation (peut être utilisé plus tard) -->
<div id="confirmModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Confirmation
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" id="modal-message">
                                Êtes-vous sûr de vouloir effectuer cette action?
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="confirmBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Confirmer
                </button>
                <button type="button" id="cancelBtn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

