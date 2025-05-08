<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Mariee;
use App\Models\Traiteur;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{

    public function showRegisterForm()
    {
        $roles = Role::whereNotIn('name', ['admin'])->get();

        return view('auth.register', compact('roles'));
    }


    public function register(Request $request)
    {

        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        $role = Role::find($request->role_id);

        if ($role->name === 'mariee') {

            $request->validate([
                'groom_name' => 'required|string|max:255',
                'bride_name' => 'required|string|max:255',
            ]);

            // Création du profil couple
            Mariee::create([
                'user_id' => $user->id,
                'groom_name' => $request->groom_name,
                'bride_name' => $request->bride_name,
                'city' => $request->city,
            ]);
        } elseif ($role->name === 'traiteur') {
            
            $request->validate([
                'manager_name' => 'required|string|max:255',
                'registration_number' => 'required|string|max:255|unique:traiteurs',
                'phone_number' => 'required|string|max:20',
                'city' => 'required|string|max:255',
            ]);



            Traiteur::create([
                'user_id' => $user->id,
                'manager_name' => $request->manager_name,
                'registration_number' => $request->registration_number,
                'phone_number' => $request->phone_number,
                'city' => $request->city,
                'is_verified' => false,
            ]);
        }
        Auth::login($user);


        event(new Registered($user));


        return redirect()->route('verification.notice')
            ->with('success', 'Votre compte a été créé avec succès. Veuillez vérifier votre adresse email.');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }


    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Tentative de connexion
    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();

        // Vérification si l'email est vérifié
        if (!Auth::user()->hasVerifiedEmail()) {
            Auth::logout();

            return redirect()->route('login')
                ->with('error', 'Vous devez vérifier votre adresse email avant de vous connecter.');
        }

        // Redirection selon le rôle (uniquement pour l'admin)
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isTraiteur()) {
            // Vérification si le traiteur est vérifié par l'admin
            if (!$user->traiteur->is_verified) {
                Auth::logout();

                return redirect()->route('login')
                    ->with('error', 'Votre compte traiteur est en attente de validation par l\'administrateur.');
            }
        }

        // Pour les mariées et traiteurs vérifiés, redirection vers la page d'accueil
        return redirect()->route('welcome');
    }

    // Échec de la connexion
    return back()->withErrors([
        'email' => 'Les informations d\'identification fournies ne correspondent pas à nos enregistrements.',
    ])->withInput($request->except('password'));
}


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome');
    }
}
