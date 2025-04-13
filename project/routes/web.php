<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\TraiteurMenuController;
use App\Http\Controllers\TraiteurNegafaController;
use App\Http\Controllers\TraiteurClothingController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::get('/services', function () {
    return view('coming-soon');
});

Route::get('/about', function () {
    return view('pages/about');
});

Route::get('/contact', function () {
    return view('coming-soon');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



// Routes de vérification d'email
Route::get('/email/verify', [VerificationController::class, 'notice'])
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [VerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');



// Routes pour les mariées
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/planning', function () {
        // Vérifier si l'utilisateur est une mariée
        if (!Auth::user()->isMariee()) {
            return redirect()->route('welcome')->with('error', 'Accès non autorisé.');
        }

        return view('planning');
    })->name('planning');
    Route::get('/profil-mariee', function () {
        return view('mariee.profile');
    })->name('mariee.profile');

    Route::get('/messagerie', function () {
        return view('messagerie');
    })->name('messagerie');
});

// Routes pour les traiteurs
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profil-traiteur', function () {
        return view('traiteur.profile');
    })->name('traiteur.profile');

    // Remplacé cette route
    Route::get('/gerer-services', function() {
        // Récupère toutes les catégories de services
        $categories = DB::table('service_categories')->get();

        // Par défaut, on affiche une page d'accueil générale
        return view('traiteur.gerer_services', [
            'activeTab' => 'accueil',
            'categories' => $categories,
            'contentView' => view('traiteur.services.accueil')->render()
        ]);
    })->name('traiteur.gerer-services');

    Route::get('/reservations', function () {
        return view('traiteur.reservations');
    })->name('traiteur.reservations');

    Route::get('/messagerie', function () {
        return view('messagerie');
    })->name('messagerie');
});

// Route pour l'admin dashboard
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});


// Routes pour la gestion des services par les traiteurs
Route::middleware(['auth', 'verified'])->group(function () {
    // Route principale pour la gestion des services
    Route::get('/traiteur/gerer-services', function() {
        // Récupère toutes les catégories de services
        $categories = DB::table('service_categories')->get();

        // Par défaut, on affiche une page d'accueil générale
        return view('traiteur.gerer_services', [
            'activeTab' => 'accueil',
            'categories' => $categories,
            'contentView' => view('traiteur.services.accueil')->render()
        ]);
    })->name('traiteur.gerer-services');

    // Routes pour le service Menu
    Route::get('/traiteur/services/menu', [TraiteurMenuController::class, 'index'])->name('traiteur.services.menu.index');
    Route::get('/traiteur/services/menu/create', [TraiteurMenuController::class, 'create'])->name('traiteur.services.menu.create');
    Route::post('/traiteur/services/menu', [TraiteurMenuController::class, 'store'])->name('traiteur.services.menu.store');
    Route::get('/traiteur/services/menu/{menu}', [TraiteurMenuController::class, 'show'])->name('traiteur.services.menu.show');
    Route::get('/traiteur/services/menu/{menu}/edit', [TraiteurMenuController::class, 'edit'])->name('traiteur.services.menu.edit');
    Route::put('/traiteur/services/menu/{menu}', [TraiteurMenuController::class, 'update'])->name('traiteur.services.menu.update');
    Route::delete('/traiteur/services/menu/{menu}', [TraiteurMenuController::class, 'destroy'])->name('traiteur.services.menu.destroy');

    // Routes pour les items de menu
    Route::get('/traiteur/services/menu/{menu}/items/create', [TraiteurMenuController::class, 'createMenuItem'])->name('traiteur.services.menu.items.create');
    Route::post('/traiteur/services/menu/{menu}/items', [TraiteurMenuController::class, 'storeMenuItem'])->name('traiteur.services.menu.items.store');
    Route::get('/traiteur/services/menu/{menu}/items/{item}/edit', [TraiteurMenuController::class, 'editMenuItem'])->name('traiteur.services.menu.items.edit');
    Route::put('/traiteur/services/menu/{menu}/items/{item}', [TraiteurMenuController::class, 'updateMenuItem'])->name('traiteur.services.menu.items.update');
    Route::delete('/traiteur/services/menu/{menu}/items/{item}', [TraiteurMenuController::class, 'destroyMenuItem'])->name('traiteur.services.menu.items.destroy');

    // Routes pour le service Vêtements
Route::get('/traiteur/services/vetements', [TraiteurClothingController::class, 'index'])->name('traiteur.services.vetements.index');
Route::get('/traiteur/services/vetements/traditional', [TraiteurClothingController::class, 'showTraditional'])->name('traiteur.services.vetements.traditional');
Route::get('/traiteur/services/vetements/modern', [TraiteurClothingController::class, 'showModern'])->name('traiteur.services.vetements.modern');

// Routes pour les items de vêtements
Route::post('/traiteur/services/vetements/{style}/items', [TraiteurClothingController::class, 'storeClothingItem'])->name('traiteur.services.vetements.items.store');
Route::get('/traiteur/services/vetements/{style}/items/{item}/edit', [TraiteurClothingController::class, 'editClothingItem'])->name('traiteur.services.vetements.items.edit');
Route::put('/traiteur/services/vetements/{style}/items/{item}', [TraiteurClothingController::class, 'updateClothingItem'])->name('traiteur.services.vetements.items.update');
Route::delete('/traiteur/services/vetements/{style}/items/{item}', [TraiteurClothingController::class, 'destroyClothingItem'])->name('traiteur.services.vetements.items.destroy');

  // Liste des négafas
  Route::get('/traiteur/services/negafa', [TraiteurNegafaController::class, 'index'])
  ->name('traiteur.services.negafa.index');

// Création d'une négafa
Route::get('/traiteur/services/negafa/create', [TraiteurNegafaController::class, 'create'])
  ->name('traiteur.services.negafa.create');
Route::post('/traiteur/services/negafa', [TraiteurNegafaController::class, 'store'])
  ->name('traiteur.services.negafa.store');

// Affichage, édition et suppression d'une négafa
    Route::get('/traiteur/services/negafa/{negafa}', [TraiteurNegafaController::class, 'show'])
    ->name('traiteur.services.negafa.show');
    Route::get('/traiteur/services/negafa/{negafa}/edit', [TraiteurNegafaController::class, 'edit'])
    ->name('traiteur.services.negafa.edit');
    Route::put('/traiteur/services/negafa/{negafa}', [TraiteurNegafaController::class, 'update'])
    ->name('traiteur.services.negafa.update');
    Route::delete('/traiteur/services/negafa/{negafa}', [TraiteurNegafaController::class, 'destroy'])
    ->name('traiteur.services.negafa.destroy');

    // Gestion du portfolio 
    Route::get('/traiteur/services/negafa/{negafa}/portfolio/create', [TraiteurNegafaController::class, 'createPortfolioItem'])
    ->name('traiteur.services.negafa.portfolio.create');
    Route::post('/traiteur/services/negafa/{negafa}/portfolio', [TraiteurNegafaController::class, 'storePortfolioItem'])
    ->name('traiteur.services.negafa.portfolio.store');
    Route::delete('/traiteur/services/negafa/{negafa}/portfolio/{item}', [TraiteurNegafaController::class, 'destroyPortfolioItem'])
    ->name('traiteur.services.negafa.portfolio.destroy');


});
