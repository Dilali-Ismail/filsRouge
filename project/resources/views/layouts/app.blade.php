<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Alf Mabrouk') }} - @yield('title', 'Bienvenue')</title>

    <!-- Polices Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['"Open Sans"', '-apple-system', 'system-ui', 'BlinkMacSystemFont', '"Segoe UI"', 'Roboto', '"Helvetica Neue"', 'Arial', 'sans-serif'],
                        'display': ['"Playfair Display"', 'serif'],
                    }
                }
            }
        }
    </script>

    <style type="text/css">
        body {
            font-family: "Open Sans", -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: "Playfair Display", serif;
        }
    </style>

    <!-- Styles supplémentaires -->
    @yield('styles')
</head>
<body class="min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo à gauche -->
                <div class="flex-shrink-0">
                    <a href="{{ url('/') }}" class="flex items-center">
                        <img src="{{ asset('images/logo.png') }}" alt="Alf Mabrouk Logo" class="h-16 w-auto">
                    </a>
                </div>

                <!-- Liens de navigation au centre -->
                <!-- Liens de navigation au centre -->
<div class="hidden md:flex items-center justify-center flex-1">
    <!-- Lien Accueil pour tous -->
    <a href="{{ url('/') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-[#C08081]">Accueil</a>

    @guest
        <!-- Liens pour visiteurs (non connectés) -->
        <a href="{{ url('/services') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-[#C08081]">Services</a>
        <a href="{{ url('/about') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-[#C08081]">À propos</a>
        <a href="{{ url('/contact') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-[#C08081]">Contact</a>
    @else
        @if(Auth::user()->isMariee())
            <!-- Liens pour les mariées -->
            <a href="{{ url('/planning') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-[#C08081]">Planning</a>
            <a href="{{ url('/services') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-[#C08081]">Services</a>
            <a href="{{ url('/about') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-[#C08081]">À propos</a>
            <a href="{{ url('/contact') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-[#C08081]">Contact</a>
            <a href="{{ url('/messagerie') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-[#C08081]">Messagerie</a>
        @elseif(Auth::user()->isTraiteur())
            <!-- Liens pour les traiteurs -->
            <a href="{{ url('/services') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-[#C08081]">Services</a>
            <a href="{{ url('/gerer-services') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-[#C08081]">Gérer services</a>
            <a href="{{ url('/messagerie') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-[#C08081]">Messagerie</a>
            <a href="{{ url('/reservations') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-[#C08081]">Réservations</a>
        @elseif(Auth::user()->isAdmin())
            <!-- Liens pour les admins -->
            <a href="{{ url('/admin/dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-[#C08081]">Dashboard</a>
        @endif
    @endguest
</div>

                <!-- Boutons à droite -->
                <div class="hidden md:flex items-center">
                    @guest
                        <!-- Boutons pour les visiteurs (non connectés) -->
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-[#333333] hover:text-[#C08081] transition-colors duration-300 border border-transparent hover:border-[#FADADD] rounded-lg">Se connecter</a>
                        <a href="{{ route('register') }}" class="ml-3 px-4 py-2 rounded-lg text-sm font-medium text-[#333333] bg-[#FADADD] hover:bg-[#C08081] hover:text-white transition duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">S'inscrire</a>
                    @else
                        <!-- Profil et déconnexion pour les utilisateurs connectés -->
                        <div class="relative ml-3 flex items-center">
                            @if(Auth::user()->isMariee() || Auth::user()->isTraiteur())
                                <!-- Icône de profil pour mariées et traiteurs -->
                                <a href="{{ Auth::user()->isMariee() ? url('/profil-mariee') : url('/profil-traiteur') }}" class="mr-4 text-gray-700 hover:text-[#C08081]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </a>
                            @endif

                            <!-- Bouton de déconnexion -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="px-4 py-2 rounded-lg text-sm font-medium text-[#333333] bg-gray-100 hover:bg-[#FADADD] transition duration-300">
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    @endguest
                </div>

                <!-- Menu mobile (hamburger) -->
                <div class="flex md:hidden">
                    <button type="button" class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-[#C08081] hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-[#FADADD]" aria-expanded="false">
                        <span class="sr-only">Ouvrir le menu</span>
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Menu mobile (caché par défaut) -->
           <!-- Menu mobile (caché par défaut) -->
<div class="mobile-menu hidden md:hidden">
    <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
        <!-- Lien Accueil pour tous -->
        <a href="{{ url('/') }}" class="block px-3 py-2 rounded-md text-base font-medium text-[#333333] hover:text-[#C08081] hover:bg-[#FAF9F6]">Accueil</a>

        @guest
            <!-- Liens pour visiteurs (non connectés) sur mobile -->
            <a href="{{ url('/services') }}" class="block px-3 py-2 rounded-md text-base font-medium text-[#333333] hover:text-[#C08081] hover:bg-[#FAF9F6]">Services</a>
            <a href="{{ url('/about') }}" class="block px-3 py-2 rounded-md text-base font-medium text-[#333333] hover:text-[#C08081] hover:bg-[#FAF9F6]">À propos</a>
            <a href="{{ url('/contact') }}" class="block px-3 py-2 rounded-md text-base font-medium text-[#333333] hover:text-[#C08081] hover:bg-[#FAF9F6]">Contact</a>
        @else
            @if(Auth::user()->isMariee())
                <!-- Liens pour les mariées sur mobile -->
                <a href="{{ url('/planning') }}" class="block px-3 py-2 rounded-md text-base font-medium text-[#333333] hover:text-[#C08081] hover:bg-[#FAF9F6]">Planning</a>
                <a href="{{ url('/services') }}" class="block px-3 py-2 rounded-md text-base font-medium text-[#333333] hover:text-[#C08081] hover:bg-[#FAF9F6]">Services</a>
                <a href="{{ url('/about') }}" class="block px-3 py-2 rounded-md text-base font-medium text-[#333333] hover:text-[#C08081] hover:bg-[#FAF9F6]">À propos</a>
                <a href="{{ url('/contact') }}" class="block px-3 py-2 rounded-md text-base font-medium text-[#333333] hover:text-[#C08081] hover:bg-[#FAF9F6]">Contact</a>
                <a href="{{ url('/messagerie') }}" class="block px-3 py-2 rounded-md text-base font-medium text-[#333333] hover:text-[#C08081] hover:bg-[#FAF9F6]">Messagerie</a>
                <a href="{{ url('/profil-mariee') }}" class="block px-3 py-2 rounded-md text-base font-medium text-[#333333] hover:text-[#C08081] hover:bg-[#FAF9F6]">Profil</a>
            @elseif(Auth::user()->isTraiteur())
                <!-- Liens pour les traiteurs sur mobile -->
                <a href="{{ url('/services') }}" class="block px-3 py-2 rounded-md text-base font-medium text-[#333333] hover:text-[#C08081] hover:bg-[#FAF9F6]">Services</a>
                <a href="{{ url('/gerer-services') }}" class="block px-3 py-2 rounded-md text-base font-medium text-[#333333] hover:text-[#C08081] hover:bg-[#FAF9F6]">Gérer services</a>
                <a href="{{ url('/messagerie') }}" class="block px-3 py-2 rounded-md text-base font-medium text-[#333333] hover:text-[#C08081] hover:bg-[#FAF9F6]">Messagerie</a>
                <a href="{{ url('/reservations') }}" class="block px-3 py-2 rounded-md text-base font-medium text-[#333333] hover:text-[#C08081] hover:bg-[#FAF9F6]">Réservations</a>
                <a href="{{ url('/profil-traiteur') }}" class="block px-3 py-2 rounded-md text-base font-medium text-[#333333] hover:text-[#C08081] hover:bg-[#FAF9F6]">Profil</a>
            @elseif(Auth::user()->isAdmin())
                <!-- Liens pour les admins sur mobile -->
                <a href="{{ url('/admin/dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-[#333333] hover:text-[#C08081] hover:bg-[#FAF9F6]">Dashboard</a>
            @endif
        @endguest
    </div>

    <!-- Le reste du code reste inchangé -->
</div>
        </div>
    </nav>



    <!-- Contenu principal -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Logo et description -->
                <div class="col-span-1">
                    <img src="{{ asset('images/logo-white.png') }}" alt="Alf Mabrouk Logo" class="h-10 w-auto mb-4">
                    <p class="text-gray-300">Alf Mabrouk - La plateforme qui simplifie l'organisation de votre mariage.</p>
                </div>

                <!-- Liens rapides -->
                <div class="col-span-1">
                    <h3 class="text-lg font-semibold mb-4">Liens rapides</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ url('/about') }}" class="text-gray-300 hover:text-white">À propos</a></li>
                        <li><a href="{{ url('/contact') }}" class="text-gray-300 hover:text-white">Contact</a></li>
                    </ul>
                </div>

                <!-- Réseaux sociaux -->
                <div class="col-span-1">
                    <h3 class="text-lg font-semibold mb-4">Suivez-nous</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white">
                            <span class="sr-only">Twitter</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-gray-300">
                <p>© {{ date('Y') }} Alf Mabrouk. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
     <!-- Script pour le menu mobile -->
     <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.querySelector('.mobile-menu-button');
            const mobileMenu = document.querySelector('.mobile-menu');

            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
