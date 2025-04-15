<?php

namespace App\Http\Controllers;

use App\Services\NegafaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TraiteurNegafaController extends Controller
{
    protected $negafaService;

    public function __construct(NegafaService $negafaService)
    {
        $this->negafaService = $negafaService;
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isTraiteur()) {
                return redirect()->route('welcome')->with('error', 'Accès non autorisé.');
            }
            return $next($request);
        });
    }

    /**
     * Affiche la liste des négafas
     */
    public function index()
    {
        $traiteurId = Auth::user()->traiteur->id;
        $negafaServices = $this->negafaService->getAllNegafaServicesByTraiteur($traiteurId);
        $negafas = [];

        foreach ($negafaServices as $service) {
            $serviceNegafas = $service->negafas;
            $negafas = array_merge($negafas, $serviceNegafas->all());
        }

        // Si la requête est AJAX, retourne seulement la partie contenu
        if (request()->ajax()) {
            return view('traiteur.services.negafa.index_content', compact('negafas'));
        }

        // Récupère toutes les catégories de services pour le menu latéral
        $categories = DB::table('service_categories')->get();

        return view('traiteur.gerer_services', [
            'activeTab' => 'negafa',
            'categories' => $categories,
            'contentView' => view('traiteur.services.negafa.index_content', compact('negafas'))->render()
        ]);
    }

    /**
     * Affiche le formulaire de création d'une négafa
     */
    public function create()
    {
        return view('traiteur.services.negafa.create');
    }

    /**
     * Enregistre une nouvelle négafa
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'experience' => 'nullable|string|max:255',
        ]);

        $traiteurId = Auth::user()->traiteur->id;

        // Récupère ou crée un service de type négafa
        $negafaService = $this->negafaService->getOrCreateNegafaService($traiteurId);

        // Création de la négafa
        $negafa = $this->negafaService->createNegafa($negafaService->id, $request->all());

        return redirect()->route('traiteur.services.negafa.index', $negafa->id)
            ->with('success', 'La négafa a été ajoutée avec succès.');
    }

    /**
     * Affiche les détails d'une négafa
     */
    public function show($id)
    {
        $negafa = $this->negafaService->getNegafaWithPortfolio($id);

        // Vérifie que la négafa appartient au traiteur connecté
        if (!$this->negafaBelongsToTraiteur($negafa)) {
            return redirect()->route('traiteur.services.negafa.index')
                ->with('error', 'Vous n\'êtes pas autorisé à voir cette négafa.');
        }

        return view('traiteur.services.negafa.show', compact('negafa'));
    }

    /**
     * Affiche le formulaire d'édition d'une négafa
     */
    public function edit($id)
    {
        $negafa = $this->negafaService->getNegafaWithPortfolio($id);

        // Vérifie que la négafa appartient au traiteur connecté
        if (!$this->negafaBelongsToTraiteur($negafa)) {
            return redirect()->route('traiteur.services.negafa.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette négafa.');
        }

        return view('traiteur.services.negafa.edit', compact('negafa'));
    }

    /**
     * Met à jour une négafa
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'experience' => 'nullable|string|max:255',
        ]);

        $negafa = $this->negafaService->getNegafaWithPortfolio($id);

        // Vérifie que la négafa appartient au traiteur connecté
        if (!$this->negafaBelongsToTraiteur($negafa)) {
            return redirect()->route('traiteur.services.negafa.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette négafa.');
        }

        // Mise à jour de la négafa
        $this->negafaService->updateNegafa($id, $request->all());

        return redirect()->route('traiteur.services.negafa.show', $id)
            ->with('success', 'La négafa a été mise à jour avec succès.');
    }

    /**
     * Supprime une négafa
     */
    public function destroy($id)
    {
        $negafa = $this->negafaService->getNegafaWithPortfolio($id);

        // Vérifie que la négafa appartient au traiteur connecté
        if (!$this->negafaBelongsToTraiteur($negafa)) {
            return redirect()->route('traiteur.services.negafa.index')
                ->with('error', 'Vous n\'êtes pas autorisé à supprimer cette négafa.');
        }

        // Supprime la négafa
        $this->negafaService->deleteNegafa($id);

        return redirect()->route('traiteur.services.negafa.index')
            ->with('success', 'La négafa a été supprimée avec succès.');
    }

    /**
     * Affiche le formulaire d'ajout d'élément au portfolio
     */
    public function createPortfolioItem($negafaId)
    {
        $negafa = $this->negafaService->getNegafaWithPortfolio($negafaId);

        // Vérifie que la négafa appartient au traiteur connecté
        if (!$this->negafaBelongsToTraiteur($negafa)) {
            return redirect()->route('traiteur.services.negafa.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette négafa.');
        }

        return view('traiteur.services.negafa.portfolio.create', compact('negafa'));
    }

    /**
     * Enregistre un nouvel élément au portfolio
     */
    public function storePortfolioItem(Request $request, $negafaId)
{
    $request->validate([
        'type' => 'required|in:image,video',
        'file' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:48000', // 48MB
        'title' => 'nullable|string|max:255',
        'description' => 'nullable|string',
    ]);

    $negafa = $this->negafaService->getNegafaWithPortfolio($negafaId);

    // Vérifie que la négafa appartient au traiteur connecté
    if (!$this->negafaBelongsToTraiteur($negafa)) {
        return redirect()->route('traiteur.services.negafa.index')
            ->with('error', 'Vous n\'êtes pas autorisé à modifier cette négafa.');
    }

    // Ajout de l'élément au portfolio
    $this->negafaService->addPortfolioItem($negafaId, $request->all());

    return redirect()->route('traiteur.services.negafa.show', $negafaId)
        ->with('success', 'L\'élément a été ajouté au portfolio avec succès.');
}

    /**
     * Supprime un élément du portfolio
     */
    public function destroyPortfolioItem($negafaId, $itemId)
    {
        $negafa = $this->negafaService->getNegafaWithPortfolio($negafaId);

        // Vérifie que la négafa appartient au traiteur connecté
        if (!$this->negafaBelongsToTraiteur($negafa)) {
            return redirect()->route('traiteur.services.negafa.index')
                ->with('error', 'Vous n\'êtes pas autorisé à effectuer cette action.');
        }

        // Supprime l'élément du portfolio
        $this->negafaService->deletePortfolioItem($itemId);

        return redirect()->route('traiteur.services.negafa.show', $negafaId)
            ->with('success', 'L\'élément a été supprimé du portfolio avec succès.');
    }

    /**
     * Méthode auxiliaire pour vérifier si une négafa appartient au traiteur connecté
     */
    private function negafaBelongsToTraiteur($negafa)
    {
        $traiteurId = Auth::user()->traiteur->id;

        return $negafa->service->traiteur_id == $traiteurId;
    }
}
