<div class="mb-6">
    <h2 class="text-2xl font-semibold text-[#333333] mb-6 text-center">Gestion des Vêtements</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Carte Vêtements Traditionnels -->
        <a href="{{ route('traiteur.services.vetements.traditional') }}" class="group">
            <div class="relative h-60 rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 transform group-hover:-translate-y-1">
                <img src="{{ asset('images/traditional-clothing.webp') }}" alt="Vêtements traditionnels" class="w-full h-full object-cover">

                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-6">
                    <div class="w-full">
                        <h3 class="text-white text-2xl font-semibold mb-2">Vêtements Traditionnels</h3>
                        <p class="text-gray-200 mb-4">Gestion des caftans, jabadors et costumes traditionnels</p>
                        <span class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-lg group-hover:bg-[#FADADD] group-hover:text-[#333333] transition-colors duration-300">
                            Gérer les vêtements traditionnels
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </a>

        <!-- Carte Vêtements Modernes -->
        <a href="{{ route('traiteur.services.vetements.modern') }}" class="group">
            <div class="relative h-60 rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 transform group-hover:-translate-y-1">
                <img src="{{ asset('images/modern-clothing.jpg') }}" alt="Vêtements modernes" class="w-full h-full object-cover">

                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-6">
                    <div class="w-full">
                        <h3 class="text-white text-2xl font-semibold mb-2">Vêtements Modernes</h3>
                        <p class="text-gray-200 mb-4">Gestion des robes et costumes contemporains</p>
                        <span class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-lg group-hover:bg-[#FADADD] group-hover:text-[#333333] transition-colors duration-300">
                            Gérer les vêtements modernes
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
