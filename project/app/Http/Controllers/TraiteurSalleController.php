<?php

namespace App\Http\Controllers;

use App\Services\SalleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TraiteurSalleController extends Controller
{
    protected $salleService;

    public function __construct(SalleService $salleService)
    {
        $this->salleService = $salleService;
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isTraiteur()) {
                return redirect()->route('welcome')->with('error', 'Accès non autorisé.');
            }
            return $next($request);
        });
    }

    /**
     * Affiche la liste des salles
     */
    public function index()
    {
        $traiteurId = Auth::user()->traiteur->id;
        $salleServices = $this->salleService->getAllSalleServicesByTraiteur($traiteurId);
        $salles = [];

        foreach ($salleServices as $service) {
            $serviceSalles = $service->salles;
            $salles = array_merge($salles, $serviceSalles->all());
        }

        // Si la requête est AJAX, retourne seulement la partie contenu
        if (request()->ajax()) {
            return view('traiteur.services.salle.index_content', compact('salles'));
        }

        // Récupère toutes les catégories de services pour le menu latéral
        $categories = DB::table('service_categories')->get();

        return view('traiteur.gerer_services', [
            'activeTab' => 'salles',
            'categories' => $categories,
            'contentView' => view('traiteur.services.salle.index_content', compact('salles'))->render()
        ]);
    }

    /**
     * Affiche le formulaire de création d'une salle
     */
    public function create()
    {
        return view('traiteur.services.salle.create');
    }

    /**
     * Enregistre une nouvelle salle
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'location' => 'required|string|max:255',
            'tables_count' => 'required|integer|min:1',
        ]);

        $traiteurId = Auth::user()->traiteur->id;

        // Récupère ou crée un service de type salle
        $salleService = $this->salleService->getOrCreateSalleService($traiteurId);

        // Création de la salle
        $salle = $this->salleService->createSalle($salleService->id, $request->all());

        return redirect()->route('traiteur.services.salle.index')
            ->with('success', 'La salle a été ajoutée avec succès.');
    }

    /**
     * Affiche les détails d'une salle
     */
    public function show($id)
    {
        $salle = $this->salleService->getSalle($id);

        // Vérifie que la salle appartient au traiteur connecté
        if (!$this->salleBelongsToTraiteur($salle)) {
            return redirect()->route('traiteur.services.salle.index')
                ->with('error', 'Vous n\'êtes pas autorisé à voir cette salle.');
        }

        return view('traiteur.services.salle.show', compact('salle'));
    }

    /**
     * Affiche le formulaire d'édition d'une salle
     */
    public function edit($id)
    {
        $salle = $this->salleService->getSalle($id);

        // Vérifie que la salle appartient au traiteur connecté
        if (!$this->salleBelongsToTraiteur($salle)) {
            return redirect()->route('traiteur.services.salle.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette salle.');
        }

        return view('traiteur.services.salle.edit', compact('salle'));
    }

    /**
     * Met à jour une salle
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'location' => 'required|string|max:255',
            'tables_count' => 'required|integer|min:1',
        ]);

        $salle = $this->salleService->getSalle($id);

        // Vérifie que la salle appartient au traiteur connecté
        if (!$this->salleBelongsToTraiteur($salle)) {
            return redirect()->route('traiteur.services.salle.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette salle.');
        }

        // Mise à jour de la salle
        $this->salleService->updateSalle($id, $request->all());

        return redirect()->route('traiteur.services.salle.index')
            ->with('success', 'La salle a été mise à jour avec succès.');
    }

    /**
     * Supprime une salle
     */
    public function destroy($id)
    {
        $salle = $this->salleService->getSalle($id);

        // Vérifie que la salle appartient au traiteur connecté
        if (!$this->salleBelongsToTraiteur($salle)) {
            return redirect()->route('traiteur.services.salle.index')
                ->with('error', 'Vous n\'êtes pas autorisé à supprimer cette salle.');
        }

        // Supprime la salle
        $this->salleService->deleteSalle($id);

        return redirect()->route('traiteur.services.salle.index')
            ->with('success', 'La salle a été supprimée avec succès.');
    }

    /**
     * Méthode auxiliaire pour vérifier si une salle appartient au traiteur connecté
     */
    private function salleBelongsToTraiteur($salle)
    {
        $traiteurId = Auth::user()->traiteur->id;
        return $salle->service->traiteur_id == $traiteurId;
    }
}
