<?php

namespace App\Http\Controllers;

use App\Models\Traiteur;
use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\Auth;

class PlanningController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(){

        if(!Auth::user()->isMariee()){
            return redirect()->route('welcome')->with('error', 'Accès réservé aux  mariées.');;
        }

        $traiteurs = Traiteur::where('is_verified',true)->get();

        $mariee = Auth::user()->mariee();
        $recommendedTraiteurs = collect();

        if($mariee && $mariee->city){
            $recommendedTraiteurs = Traiteur::where('city', $mariee->city)
                                    ->where('is_verified',true)->get();

            return view('planning_inex',compact($traiteurs, $recommendedTraiteurs));
        }

    }

    public function checkAvailability(Request $request){

        $request->validate([
             'traiteur_id' => 'required|exist:traiteur,id',
             'date' => 'required|date|after:today'
        ]);

        $traiteurId = $request->traiteur_id;
        $date = $request->date;

        $traiteur =  Traiteur::failOrFind($traiteurId);

        $isAvailable = $traiteur->isAvailableOn($date);

        return response()->json([
            'available' => $isAvailable ,
            'traiteur' => $traiteur->manager_name ,
            'date' => $date
        ]);


    }
    //mise a  jour la date de mariage
    public function updateWeddingDate(Request $request){

        $request->validate([
            'wedding_date' => 'required|date|after:today'
        ]);

        $mariee = Auth::user()->mariee;
        $mariee->wedding_date = $request->wedding_date;
        $mariee->save();

        return response()->json([
            'success' => true,
            'message' => 'Date de mariage mise à jour avec succès'
        ]);


    }

    public function showTraiteurServices($traiteurId){

        if (!Auth::user()->isMariee()){

            return redirect()->route('welcome')
            ->with('error', 'Accès réservé aux futur(e)s marié(e)s.');
        }

       $traiteur = Traiteur::with(['services.category'])->findOrFail($traiteurId);
       $categories = ServiceCategory::all();

        return view('planning.services', compact('traiteur', 'categories'));
    }



}
