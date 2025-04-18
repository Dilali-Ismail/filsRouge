<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Traiteur;
use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use App\Models\TraiteurAvailability;
use Illuminate\Support\Facades\Auth;

class PlanningController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(){

        if (!Auth::user()->isMariee()) {
            return redirect()->route('welcome')->with('error', 'Accès réservé aux mariées.');
        }


        $traiteurs = Traiteur::where('is_verified', true)->with('user')->get();


        $recommendedTraiteurs = collect();


        $mariee = Auth::user()->mariee;


        if ($mariee && $mariee->city) {
            $recommendedTraiteurs = Traiteur::where('city', $mariee->city)
                                    ->where('is_verified', true)
                                    ->with('user')
                                    ->get();
        }


        return view('planning.index', compact('traiteurs', 'recommendedTraiteurs'));

    }

    public function getTraiteurDetails($id)
    {
        $traiteur = Traiteur::with('user')->findOrFail($id);

        return response()->json([
            'traiteur' => $traiteur
        ]);
    }


public function getAvailableDates($traiteurId)
{

    $availabilities = TraiteurAvailability::where('traiteur_id', $traiteurId)
        ->where('is_available', true)
        ->get()
        ->pluck('date')
        ->map(function($date) {
            return Carbon::parse($date)->format('Y-m-d');
        });

    return response()->json([
        'availableDates' => $availabilities
    ]);
}




}
