<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlanningController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\MarieProfileController;
use App\Http\Controllers\TraiteurMenuController;
use App\Http\Controllers\TraiteurSalleController;
use App\Http\Controllers\TraiteurMakeupController;
use App\Http\Controllers\TraiteurNegafaController;
use App\Http\Controllers\TraiteurAmariyaController;
use App\Http\Controllers\TraiteurClothingController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\TraiteurAnimationController;
use App\Http\Controllers\TraiteurDecorationController;
use App\Http\Controllers\TraiteurPhotographerController;
use App\Http\Controllers\TraiteurReservationsController;
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

//=========================
// ROUTES PUBLIQUES
//=========================

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

//=========================
// ROUTES D'AUTHENTIFICATION
//=========================

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

//=========================
// ROUTES COMMUNES
//=========================

Route::middleware(['auth', 'verified'])->group(function () {
    // Messagerie commune à tous les rôles
    Route::get('/messagerie', [MessageController::class, 'index'])->name('messagerie');
    Route::get('/conversations/{conversation}/messages', [MessageController::class, 'getMessages']);
    Route::post('/conversations/{conversation}/messages', [MessageController::class, 'sendMessage']);
    Route::post('/conversations', [MessageController::class, 'createConversation']);
});

//=========================
// ROUTES POUR LES MARIÉES
//=========================

Route::middleware(['auth', 'verified', 'role:mariee'])->group(function () {

    //profil
    Route::get('/profil-mariee', [MarieProfileController::class, 'show'])->name('mariee.profile');
    Route::get('/profil-mariee/edit', [MarieProfileController::class, 'edit'])->name('mariee.profile.edit');
    Route::put('/profil-mariee/update', [MarieProfileController::class, 'update'])->name('mariee.profile.update');
    // Profil et dashboard mariée
    Route::get('/planning', function () {
        return view('planning');
    })->name('planning');

    Route::get('/profil-mariee', function () {
        return view('mariee.profile');
    })->name('mariee.profile');

    // Planning et recherche de traiteurs
    Route::get('/planning', [PlanningController::class, 'index'])->name('planning.index');
    Route::get('/planning/traiteur/{id}', [PlanningController::class, 'getTraiteurDetails'])->name('planning.traiteur.details');
    Route::get('/planning/traiteur/{traiteurId}/available-dates', [PlanningController::class, 'getAvailableDates'])->name('planning.traiteur.dates');
    Route::post('/planning/traiteur/{traiteurId}/check-date', [PlanningController::class, 'checkDateAvailability'])->name('planning.traiteur.check-date');
    Route::get('/traiteur/{traiteurId}/services', [PlanningController::class, 'services'])->name('planning.traiteur.services');
    Route::get('/planning/traiteur/{traiteurId}/all-dates-status', [PlanningController::class, 'getAllDatesStatus'])->name('planning.traiteur.all-dates-status');

    // Réservations
    Route::post('/traiteur/{traiteurId}/reservation', [ReservationController::class, 'store'])->name('reservation.store');

    // Paiements
    Route::get('/payment/{reservation}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{reservation}/session', [PaymentController::class, 'createSession'])->name('payment.create.session');
    Route::get('/payment/{reservation}/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/{reservation}/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
});

//============================
// ROUTES POUR LES TRAITEURS
//============================

Route::middleware(['auth', 'verified', 'role:traiteur'])->group(function () {
    // Profil et gestion générale
    Route::get('/profil-traiteur', function () {
        return view('traiteur.profile');
    })->name('traiteur.profile');

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

    // Réservations et disponibilités
    Route::get('/reservations', [TraiteurReservationsController::class, 'index'])->name('traiteur.reservations');
    Route::get('/traiteur/reservation/{reservation}/pdf', [TraiteurReservationsController::class, 'downloadPdf'])->name('traiteur.reservation.pdf');
    Route::post('/traiteur/availability', [AvailabilityController::class, 'store'])->name('traiteur.availability.store');

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
    Route::get('/traiteur/services/negafa', [TraiteurNegafaController::class, 'index'])->name('traiteur.services.negafa.index');
    Route::get('/traiteur/services/negafa/create', [TraiteurNegafaController::class, 'create'])->name('traiteur.services.negafa.create');
    Route::post('/traiteur/services/negafa', [TraiteurNegafaController::class, 'store'])->name('traiteur.services.negafa.store');
    Route::get('/traiteur/services/negafa/{negafa}', [TraiteurNegafaController::class, 'show'])->name('traiteur.services.negafa.show');
    Route::get('/traiteur/services/negafa/{negafa}/edit', [TraiteurNegafaController::class, 'edit'])->name('traiteur.services.negafa.edit');
    Route::put('/traiteur/services/negafa/{negafa}', [TraiteurNegafaController::class, 'update'])->name('traiteur.services.negafa.update');
    Route::delete('/traiteur/services/negafa/{negafa}', [TraiteurNegafaController::class, 'destroy'])->name('traiteur.services.negafa.destroy');
    Route::get('/traiteur/services/negafa/{negafa}/portfolio/create', [TraiteurNegafaController::class, 'createPortfolioItem'])->name('traiteur.services.negafa.portfolio.create');
    Route::post('/traiteur/services/negafa/{negafa}/portfolio', [TraiteurNegafaController::class, 'storePortfolioItem'])->name('traiteur.services.negafa.portfolio.store');
    Route::delete('/traiteur/services/negafa/{negafa}/portfolio/{item}', [TraiteurNegafaController::class, 'destroyPortfolioItem'])->name('traiteur.services.negafa.portfolio.destroy');

    // Maquillage
    Route::get('/traiteur/services/maquillage', [TraiteurMakeupController::class, 'index'])->name('traiteur.services.maquillage.index');
    Route::get('/traiteur/services/maquillage/create', [TraiteurMakeupController::class, 'create'])->name('traiteur.services.maquillage.create');
    Route::post('/traiteur/services/maquillage', [TraiteurMakeupController::class, 'store'])->name('traiteur.services.maquillage.store');
    Route::get('/traiteur/services/maquillage/{makeup}', [TraiteurMakeupController::class, 'show'])->name('traiteur.services.maquillage.show');
    Route::get('/traiteur/services/maquillage/{makeup}/edit', [TraiteurMakeupController::class, 'edit'])->name('traiteur.services.maquillage.edit');
    Route::put('/traiteur/services/maquillage/{makeup}', [TraiteurMakeupController::class, 'update'])->name('traiteur.services.maquillage.update');
    Route::delete('/traiteur/services/maquillage/{makeup}', [TraiteurMakeupController::class, 'destroy'])->name('traiteur.services.maquillage.destroy');
    Route::get('/traiteur/services/maquillage/{makeup}/portfolio/create', [TraiteurMakeupController::class, 'createPortfolioItem'])->name('traiteur.services.maquillage.portfolio.create');
    Route::post('/traiteur/services/maquillage/{makeup}/portfolio', [TraiteurMakeupController::class, 'storePortfolioItem'])->name('traiteur.services.maquillage.portfolio.store');
    Route::delete('/traiteur/services/maquillage/{makeup}/portfolio/{item}', [TraiteurMakeupController::class, 'destroyPortfolioItem'])->name('traiteur.services.maquillage.portfolio.destroy');

    // Photographe
    Route::get('/traiteur/services/photographer', [TraiteurPhotographerController::class, 'index'])->name('traiteur.services.photographer.index');
    Route::get('/traiteur/services/photographer/create', [TraiteurPhotographerController::class, 'create'])->name('traiteur.services.photographer.create');
    Route::post('/traiteur/services/photographer', [TraiteurPhotographerController::class, 'store'])->name('traiteur.services.photographer.store');
    Route::get('/traiteur/services/photographer/{photographer}', [TraiteurPhotographerController::class, 'show'])->name('traiteur.services.photographer.show');
    Route::get('/traiteur/services/photographer/{photographer}/edit', [TraiteurPhotographerController::class, 'edit'])->name('traiteur.services.photographer.edit');
    Route::put('/traiteur/services/photographer/{photographer}', [TraiteurPhotographerController::class, 'update'])->name('traiteur.services.photographer.update');
    Route::delete('/traiteur/services/photographer/{photographer}', [TraiteurPhotographerController::class, 'destroy'])->name('traiteur.services.photographer.destroy');
    Route::get('/traiteur/services/photographer/{photographer}/portfolio/create', [TraiteurPhotographerController::class, 'createPortfolioItem'])->name('traiteur.services.photographer.portfolio.create');
    Route::post('/traiteur/services/photographer/{photographer}/portfolio', [TraiteurPhotographerController::class, 'storePortfolioItem'])->name('traiteur.services.photographer.portfolio.store');
    Route::delete('/traiteur/services/photographer/{photographer}/portfolio/{item}', [TraiteurPhotographerController::class, 'destroyPortfolioItem'])->name('traiteur.services.photographer.portfolio.destroy');

    // Amariya
    Route::get('/traiteur/services/amariya', [TraiteurAmariyaController::class, 'index'])->name('traiteur.services.amariya.index');
    Route::get('/traiteur/services/amariya/create', [TraiteurAmariyaController::class, 'create'])->name('traiteur.services.amariya.create');
    Route::post('/traiteur/services/amariya', [TraiteurAmariyaController::class, 'store'])->name('traiteur.services.amariya.store');
    Route::get('/traiteur/services/amariya/{amariya}', [TraiteurAmariyaController::class, 'show'])->name('traiteur.services.amariya.show');
    Route::get('/traiteur/services/amariya/{amariya}/edit', [TraiteurAmariyaController::class, 'edit'])->name('traiteur.services.amariya.edit');
    Route::put('/traiteur/services/amariya/{amariya}', [TraiteurAmariyaController::class, 'update'])->name('traiteur.services.amariya.update');
    Route::delete('/traiteur/services/amariya/{amariya}', [TraiteurAmariyaController::class, 'destroy'])->name('traiteur.services.amariya.destroy');

    // Décoration
    Route::get('/traiteur/services/decoration', [TraiteurDecorationController::class, 'index'])->name('traiteur.services.decoration.index');
    Route::get('/traiteur/services/decoration/create', [TraiteurDecorationController::class, 'create'])->name('traiteur.services.decoration.create');
    Route::post('/traiteur/services/decoration', [TraiteurDecorationController::class, 'store'])->name('traiteur.services.decoration.store');
    Route::get('/traiteur/services/decoration/{decoration}', [TraiteurDecorationController::class, 'show'])->name('traiteur.services.decoration.show');
    Route::get('/traiteur/services/decoration/{decoration}/edit', [TraiteurDecorationController::class, 'edit'])->name('traiteur.services.decoration.edit');
    Route::put('/traiteur/services/decoration/{decoration}', [TraiteurDecorationController::class, 'update'])->name('traiteur.services.decoration.update');
    Route::delete('/traiteur/services/decoration/{decoration}', [TraiteurDecorationController::class, 'destroy'])->name('traiteur.services.decoration.destroy');

    // Salle
    Route::get('/traiteur/services/salle', [TraiteurSalleController::class, 'index'])->name('traiteur.services.salle.index');
    Route::get('/traiteur/services/salle/create', [TraiteurSalleController::class, 'create'])->name('traiteur.services.salle.create');
    Route::post('/traiteur/services/salle', [TraiteurSalleController::class, 'store'])->name('traiteur.services.salle.store');
    Route::get('/traiteur/services/salle/{salle}', [TraiteurSalleController::class, 'show'])->name('traiteur.services.salle.show');
    Route::get('/traiteur/services/salle/{salle}/edit', [TraiteurSalleController::class, 'edit'])->name('traiteur.services.salle.edit');
    Route::put('/traiteur/services/salle/{salle}', [TraiteurSalleController::class, 'update'])->name('traiteur.services.salle.update');
    Route::delete('/traiteur/services/salle/{salle}', [TraiteurSalleController::class, 'destroy'])->name('traiteur.services.salle.destroy');

    // Animation
    Route::get('/traiteur/services/animation', [TraiteurAnimationController::class, 'index'])->name('traiteur.services.animation.index');
    Route::get('/traiteur/services/animation/create', [TraiteurAnimationController::class, 'create'])->name('traiteur.services.animation.create');
    Route::post('/traiteur/services/animation', [TraiteurAnimationController::class, 'store'])->name('traiteur.services.animation.store');
    Route::get('/traiteur/services/animation/{animation}', [TraiteurAnimationController::class, 'show'])->name('traiteur.services.animation.show');
    Route::get('/traiteur/services/animation/{animation}/edit', [TraiteurAnimationController::class, 'edit'])->name('traiteur.services.animation.edit');
    Route::put('/traiteur/services/animation/{animation}', [TraiteurAnimationController::class, 'update'])->name('traiteur.services.animation.update');
    Route::delete('/traiteur/services/animation/{animation}', [TraiteurAnimationController::class, 'destroy'])->name('traiteur.services.animation.destroy');
});

//=========================
// ROUTES POUR LES ADMINISTRATEURS
//=========================

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/traiteurs/{id}/verify', [AdminController::class, 'verifyTraiteur'])->name('admin.traiteurs.verify');
    Route::delete('/admin/traiteurs/{id}/reject', [AdminController::class, 'rejectTraiteur'])->name('admin.traiteurs.reject');
});
