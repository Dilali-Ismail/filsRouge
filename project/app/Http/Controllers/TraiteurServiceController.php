<?php

namespace App\Http\Controllers;

use App\Models\Traiteur;
use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\Auth;
use App\Models\Service;

class TraiteurServiceController extends Controller
{
    public function index(Request $request, $traiteurId)
{
    // 1. Vérifier que l'utilisateur est connecté et est une mariée
    if (!Auth::check() || !Auth::user()->isMariee()) {
        return redirect()->route('login')->with('error', 'Vous devez être connecté comme mariée pour accéder à cette page.');
    }

    // 2. Récupérer le traiteur
    $traiteur = Traiteur::with('user')->findOrFail($traiteurId);

    // 3. Récupérer la date sélectionnée depuis la requête
    $selectedDate = $request->query('date');
    if (!$selectedDate) {
        return redirect()->route('planning')->with('error', 'Veuillez sélectionner une date.');
    }

    // 4. Récupérer toutes les catégories de services
    $categories = ServiceCategory::all();

    // 5. Récupérer tous les services du traiteur, organisés par catégorie
    $servicesByCategory = [];

    foreach ($categories as $category) {
        // Récupérer les services de cette catégorie pour ce traiteur
        $services = Service::where('traiteur_id', $traiteurId)
                         ->where('category_id', $category->id)
                         ->with($this->getCategoryRelation($category->name))
                         ->get();

        if ($services->count() > 0) {
            $servicesByCategory[$category->name] = $services;
        }
    }

    // 6. Renvoyer la vue avec toutes les données
    return view('services.index', [
        'traiteur' => $traiteur,
        'selectedDate' => $selectedDate,
        'servicesByCategory' => $servicesByCategory,
        'categories' => $categories
    ]);
}

/**
 * Détermine quelle relation charger selon la catégorie de service
 */
private function getCategoryRelation($categoryName)
{
    $relations = [
        'menu' => 'menuItems',
        'vetements' => 'clothing',
        'negafa' => 'negafas.portfolioItems',
        'maquillage' => 'makeups.portfolioItems',
        'photographie' => 'photographers.portfolioItems',
        'salle' => 'salles',
        'decoration' => 'decorations',
        'amariya' => 'amariyas',
        'animation' => 'animations'
    ];

    return $relations[$categoryName] ?? [];
}

}
