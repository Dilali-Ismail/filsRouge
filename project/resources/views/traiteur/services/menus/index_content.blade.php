<div class="mb-10">
    <div class="flex justify-end">
        <a href="{{ route('traiteur.services.menu.create') }}" class="px-5 py-3 bg-[#FADADD] hover:bg-[#C08081] text-[#333333] hover:text-white rounded-lg transition duration-300 flex items-center shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Créer un menu
        </a>
    </div>
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

<h2 class="text-2xl font-semibold text-[#333333] mb-6">Mes menus</h2>

@if(count($menus) > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($menus as $menu)
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 transform hover:-translate-y-1">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-[#333333] mb-3">{{ $menu->title }}</h3>
                    <p class="text-gray-600 mb-4 line-clamp-2">{{ $menu->description ?? 'Aucune description' }}</p>
                    <div class="flex justify-between items-center border-t pt-4">
                        <span class="flex items-center text-sm text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            {{ $menu->menuItems->count() ?? 0 }} items
                        </span>
                        <div class="flex space-x-3">
                            <a href="{{ route('traiteur.services.menu.show', $menu->id) }}" class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full transition duration-300" title="Voir">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            <a href="{{ route('traiteur.services.menu.edit', $menu->id) }}" class="p-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-full transition duration-300" title="Modifier">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('traiteur.services.menu.destroy', $menu->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce menu?')" class="p-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-full transition duration-300" title="Supprimer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="bg-gray-50 rounded-xl p-10 text-center shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
        </svg>
        <h3 class="text-lg font-medium text-gray-600 mb-2">Vous n'avez pas encore créé de menus</h3>
        <p class="text-gray-500 mb-6 max-w-md mx-auto">Commencez par créer un menu pour proposer vos spécialités et services à vos clients.</p>
        <a href="{{ route('traiteur.services.menu.create') }}" class="px-6 py-3 bg-[#FADADD] hover:bg-[#C08081] text-[#333333] hover:text-white rounded-lg transition duration-300 inline-flex items-center shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Créer mon premier menu
        </a>
    </div>
@endif
