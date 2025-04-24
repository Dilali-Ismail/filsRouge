<?php

namespace App\Http\Controllers;

use App\Models\Mariee;
use App\Models\Traiteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isAdmin()) {
                return redirect()->route('welcome')->with('error', 'Accès non autorisé.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        // Récupération des statistiques
        $traiteurCount = Traiteur::count();
        $marieeCount = Mariee::count();

        // Récupération des traiteurs pour le tableau (avec pagination)
        $traiteurs = Traiteur::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.dashboard', compact('traiteurCount', 'marieeCount', 'traiteurs'));
    }

    public function verifyTraiteur(Request $request, $id)
    {
        $traiteur = Traiteur::findOrFail($id);
        $traiteur->is_verified = true;
        $traiteur->save();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Le traiteur a été vérifié avec succès.');
    }

    public function rejectTraiteur(Request $request, $id)
    {
        $traiteur = Traiteur::findOrFail($id);

        // On récupère l'utilisateur associé pour le supprimer également
        $user = $traiteur->user;

        // Suppression du traiteur (et de son utilisateur grâce à la relation onDelete('cascade'))
        $traiteur->delete();
        $user->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Le compte du traiteur a été rejeté et supprimé.');
    }

}
