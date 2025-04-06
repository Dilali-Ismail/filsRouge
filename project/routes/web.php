<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
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

    Route::get('/gerer-services', function () {
        return view('traiteur.services');
    })->name('traiteur.services');

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
