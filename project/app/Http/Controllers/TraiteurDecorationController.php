<?php

namespace App\Http\Controllers;

use App\Services\DecorationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TraiteurDecorationController extends Controller
{
    protected $decorationService;

    public function __construct(DecorationService $decorationService)
    {
        $this->decorationService = $decorationService;
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isTraiteur()) {
                return redirect()->route('welcome')->with('error', 'Accès non autorisé.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $traiteurId = Auth::user()->traiteur->id;
        $decorationServices = $this->decorationService->getAllDecorationServicesByTraiteur($traiteurId);
        $decorations = [];

        foreach ($decorationServices as $service) {
            $serviceDecorations = $service->decorations;
            $decorations = array_merge($decorations, $serviceDecorations->all());
        }

        // Si la requête est AJAX, retourne seulement la partie contenu
        if (request()->ajax()) {
            return view('traiteur.services.decoration.index_content', compact('decorations'));
        }

        // Récupère toutes les catégories de services pour le menu latéral
        $categories = DB::table('service_categories')->get();

        return view('traiteur.gerer_services', [
            'activeTab' => 'decoration',
            'categories' => $categories,
            'contentView' => view('traiteur.services.decoration.index_content', compact('decorations'))->render()
        ]);
    }

    /**
     * Affiche le formulaire de création d'une décoration
     */
    public function create()
    {
        return view('traiteur.services.decoration.create');
    }

    /**
     * Enregistre une nouvelle décoration
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $traiteurId = Auth::user()->traiteur->id;

        // Récupère ou crée un service de type decoration
        $decorationService = $this->decorationService->getOrCreateDecorationService($traiteurId);

        // Création de la décoration
        $decoration = $this->decorationService->createDecoration($decorationService->id, $request->all());

        return redirect()->route('traiteur.services.decoration.index')
            ->with('success', 'La décoration a été ajoutée avec succès.');
    }

    /**
     * Affiche les détails d'une décoration
     */
    public function show($id)
    {
        $decoration = $this->decorationService->getDecoration($id);

        // Vérifie que la décoration appartient au traiteur connecté
        if (!$this->decorationBelongsToTraiteur($decoration)) {
            return redirect()->route('traiteur.services.decoration.index')
                ->with('error', 'Vous n\'êtes pas autorisé à voir cette décoration.');
        }

        return view('traiteur.services.decoration.show', compact('decoration'));
    }

    /**
     * Affiche le formulaire d'édition d'une décoration
     */
    public function edit($id)
    {
        $decoration = $this->decorationService->getDecoration($id);

        // Vérifie que la décoration appartient au traiteur connecté
        if (!$this->decorationBelongsToTraiteur($decoration)) {
            return redirect()->route('traiteur.services.decoration.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette décoration.');
        }

        return view('traiteur.services.decoration.edit', compact('decoration'));
    }

    /**
     * Met à jour une décoration
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $decoration = $this->decorationService->getDecoration($id);

        // Vérifie que la décoration appartient au traiteur connecté
        if (!$this->decorationBelongsToTraiteur($decoration)) {
            return redirect()->route('traiteur.services.decoration.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette décoration.');
        }

        // Mise à jour de la décoration
        $this->decorationService->updateDecoration($id, $request->all());

        return redirect()->route('traiteur.services.decoration.index')
            ->with('success', 'La décoration a été mise à jour avec succès.');
    }

    /**
     * Supprime une décoration
     */
    public function destroy($id)
    {
        $decoration = $this->decorationService->getDecoration($id);

        // Vérifie que la décoration appartient au traiteur connecté
        if (!$this->decorationBelongsToTraiteur($decoration)) {
            return redirect()->route('traiteur.services.decoration.index')
                ->with('error', 'Vous n\'êtes pas autorisé à supprimer cette décoration.');
        }

        // Supprime la décoration
        $this->decorationService->deleteDecoration($id);

        return redirect()->route('traiteur.services.decoration.index')
            ->with('success', 'La décoration a été supprimée avec succès.');
    }

    /**
     * Méthode auxiliaire pour vérifier si une décoration appartient au traiteur connecté
     */
    private function decorationBelongsToTraiteur($decoration)
    {
        $traiteurId = Auth::user()->traiteur->id;
        return $decoration->service->traiteur_id == $traiteurId;
    }
    
}
