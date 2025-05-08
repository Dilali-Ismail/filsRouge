<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarieProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function show()
    {
        if (!Auth::user()->isMariee()) {
            return redirect()->route('welcome')->with('error', 'Accès réservé aux mariées.');
        }

        return view('mariee.profile');
    }

    public function edit()
    {
        if (!Auth::user()->isMariee()) {
            return redirect()->route('welcome')->with('error', 'Accès réservé aux mariées.');
        }

        return view('mariee.edit_profile');
    }

    public function update(Request $request)
    {
        if (!Auth::user()->isMariee()) {
            return redirect()->route('welcome')->with('error', 'Accès réservé aux mariées.');
        }

        $request->validate([
            'groom_name' => 'required|string|max:255',
            'bride_name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'wedding_date' => 'nullable|date',
            'budget' => 'nullable|numeric',
        ]);

        $mariee = Auth::user()->mariee;
        $mariee->groom_name = $request->groom_name;
        $mariee->bride_name = $request->bride_name;
        $mariee->city = $request->city;
        $mariee->wedding_date = $request->wedding_date;
        $mariee->budget = $request->budget;
        $mariee->save();

        return redirect()->route('mariee.profile')->with('success', 'Votre profil a été mis à jour avec succès.');
    }
}
