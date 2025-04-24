<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TraiteurAvailability;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    public function store(Request $request){

        $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'available' => 'required|boolean'
        ]);

        if (!Auth::user()->isTraiteur()) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $traiteurId = Auth::user()->traiteur->id;
        $date = $request->date;
        $available = $request->available;


        // Chercher si une entrée existe déjà pour cette date
        $availability = TraiteurAvailability::where('traiteur_id', $traiteurId)
        ->where('date', $date)
        ->first();

        if ($availability) {
            // Si un enregistrement existe et que nous voulons le rendre disponible, on le supprime
            if ($available) {
                $availability->delete();
                return response()->json(['message' => 'Date marquée comme disponible', 'available' => true]);
            } else {
                // Sinon, on met à jour l'état à indisponible
                $availability->is_available = false;
                $availability->save();
                return response()->json(['message' => 'Date marquée comme indisponible', 'available' => false]);
            }
        }else {
            // Si aucun enregistrement n'existe et que nous voulons le rendre indisponible, on le crée
            if (!$available) {
                TraiteurAvailability::create([
                    'traiteur_id' => $traiteurId,
                    'date' => $date,
                    'is_available' => false
                ]);
                return response()->json(['message' => 'Date marquée comme indisponible', 'available' => false]);
            }

            // Si on veut le rendre disponible et qu'il n'existe pas, rien à faire
            return response()->json(['message' => 'Date déjà disponible', 'available' => true]);
        }



    }
}
