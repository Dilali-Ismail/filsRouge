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



        $availability = TraiteurAvailability::where('traiteur_id', $traiteurId)
        ->where('date', $date)
        ->first();

        if ($availability) {
            if ($available) {
                $availability->delete();
                return response()->json(['message' => 'Date marquée comme disponible', 'available' => true]);
            } else {
                $availability->is_available = false;
                $availability->save();
                return response()->json(['message' => 'Date marquée comme indisponible', 'available' => false]);
            }
        }else {

            if (!$available) {
                TraiteurAvailability::create([
                    'traiteur_id' => $traiteurId,
                    'date' => $date,
                    'is_available' => false
                ]);
                return response()->json(['message' => 'Date marquée comme indisponible', 'available' => false]);
            }

            
            return response()->json(['message' => 'Date déjà disponible', 'available' => true]);
        }



    }
}
