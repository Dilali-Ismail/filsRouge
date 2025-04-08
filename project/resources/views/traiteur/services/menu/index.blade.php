<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-[#333333]">Mes menus</h2>
        <a href="{{ route('traiteur.menus.create') }}" class="px-4 py-2 bg-[#FADADD] hover:bg-[#C08081] text-[#333333] hover:text-white rounded-lg transition duration-300">
            Ajouter un menu
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if($menus->isEmpty())
        <div class="text-center py-8">
            <p class="text-gray-600 mb-4">Vous n'avez pas encore créé de menu.</p>
            <p class="text-gray-600">Commencez par créer votre premier menu pour vos clients.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($menus as $menu)
                <div class="bg-white border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-[#333333] mb-2">{{ $menu->title }}</h3>
                        <p class="text-gray-600 text-sm mb-2">
                            {{ $menu->menuItems->count() }} items ·
                            Créé le {{ $menu->created_at->format('d/m/Y') }}
                        </p>

                        <div class="flex justify-end mt-4 space-x-2">
                            <a href="{{ route('traiteur.menus.show', $menu->id) }}" class="px-3 py-1 text-xs text-[#333333] border border-[#FADADD] rounded hover:bg-[#FADADD] transition-colors duration-200">
                                Voir
                            </a>
                            <a href="{{ route('traiteur.menus.edit', $menu->id) }}" class="px-3 py-1 text-xs text-[#333333] border border-gray-300 rounded hover:bg-gray-100 transition-colors duration-200">
                                Modifier
                            </a>
                            <form action="{{ route('traiteur.menus.destroy', $menu->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 text-xs text-red-600 border border-red-200 rounded hover:bg-red-100 transition-colors duration-200" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce menu ?')">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
