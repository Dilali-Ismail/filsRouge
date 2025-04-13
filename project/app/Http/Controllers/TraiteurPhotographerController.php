<?php

namespace App\Http\Controllers;

use App\Services\PhotographerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TraiteurPhotographerController extends Controller
{
    protected $photographerService;

    public function __construct(PhotographerService $photographerService)
    {
        $this->photographerService = $photographerService;
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isTraiteur()) {
                return redirect()->route('welcome')->with('error', 'Accès non autorisé.');
            }
            return $next($request);
        });
    }

    /**
     * Affiche la liste des photographes
     */
    public function index()
    {
        $traiteurId = Auth::user()->traiteur->id;
        $photographerServices = $this->photographerService->getAllPhotographerServicesByTraiteur($traiteurId);
        $photographers = [];

        foreach ($photographerServices as $service) {
            $servicePhotographers = $service->photographers;
            $photographers = array_merge($photographers, $servicePhotographers->all());
        }

        // Si la requête est AJAX, retourne seulement la partie contenu
        if (request()->ajax()) {
            return view('traiteur.services.photographer.index_content', compact('photographers'));
        }

        // Récupère toutes les catégories de services pour le menu latéral
        $categories = DB::table('service_categories')->get();

        return view('traiteur.gerer_services', [
            'activeTab' => 'photographer',
            'categories' => $categories,
            'contentView' => view('traiteur.services.photographer.index_content', compact('photographers'))->render()
        ]);
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        return view('traiteur.services.photographer.create');
    }

    /**
     * Enregistre un nouveau photographe
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

        // Récupère ou crée un service de type photographe
        $photographerService = $this->photographerService->getOrCreatePhotographerService($traiteurId);

        // Création du photographe
        $photographer = $this->photographerService->createPhotographer($photographerService->id, $request->all());

        // Redirection vers la liste des photographes
        return redirect()->route('traiteur.services.photographer.index')
            ->with('success', 'Le photographe a été ajouté avec succès.');
    }

    /**
     * Affiche les détails d'un photographe
     */
    public function show($id)
    {
        $photographer = $this->photographerService->getPhotographerWithPortfolio($id);

        // Vérifie que le photographe appartient au traiteur connecté
        if (!$this->photographerBelongsToTraiteur($photographer)) {
            return redirect()->route('traiteur.services.photographer.index')
                ->with('error', 'Vous n\'êtes pas autorisé à voir ce photographe.');
        }

        return view('traiteur.services.photographer.show', compact('photographer'));
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit($id)
    {
        $photographer = $this->photographerService->getPhotographerWithPortfolio($id);

        // Vérifie que le photographe appartient au traiteur connecté
        if (!$this->photographerBelongsToTraiteur($photographer)) {
            return redirect()->route('traiteur.services.photographer.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier ce photographe.');
        }

        return view('traiteur.services.photographer.edit', compact('photographer'));
    }

    /**
     * Met à jour un photographe
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

        $photographer = $this->photographerService->getPhotographerWithPortfolio($id);

        // Vérifie que le photographe appartient au traiteur connecté
        if (!$this->photographerBelongsToTraiteur($photographer)) {
            return redirect()->route('traiteur.services.photographer.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier ce photographe.');
        }

        // Mise à jour du photographe
        $this->photographerService->updatePhotographer($id, $request->all());

        return redirect()->route('traiteur.services.photographer.index')
            ->with('success', 'Le photographe a été mis à jour avec succès.');
    }

    /**
     * Supprime un photographe
     */
    public function destroy($id)
    {
        $photographer = $this->photographerService->getPhotographerWithPortfolio($id);

        // Vérifie que le photographe appartient au traiteur connecté
        if (!$this->photographerBelongsToTraiteur($photographer)) {
            return redirect()->route('traiteur.services.photographer.index')
                ->with('error', 'Vous n\'êtes pas autorisé à supprimer ce photographe.');
        }

        // Supprime le photographe
        $this->photographerService->deletePhotographer($id);

        return redirect()->route('traiteur.services.photographer.index')
            ->with('success', 'Le photographe a été supprimé avec succès.');
    }

    /**
     * Affiche le formulaire d'ajout d'élément au portfolio
     */
    public function createPortfolioItem($photographerId)
    {
        $photographer = $this->photographerService->getPhotographerWithPortfolio($photographerId);

        // Vérifie que le photographe appartient au traiteur connecté
        if (!$this->photographerBelongsToTraiteur($photographer)) {
            return redirect()->route('traiteur.services.photographer.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier ce photographe.');
        }

        return view('traiteur.services.photographer.portfolio.create', compact('photographer'));
    }

    /**
     * Enregistre un nouvel élément au portfolio
     */
    public function storePortfolioItem(Request $request, $photographerId)
    {
        $request->validate([
            'type' => 'required|in:image,video',
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:20480',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $photographer = $this->photographerService->getPhotographerWithPortfolio($photographerId);

        // Vérifie que le photographe appartient au traiteur connecté
        if (!$this->photographerBelongsToTraiteur($photographer)) {
            return redirect()->route('traiteur.services.photographer.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier ce photographe.');
        }

        // Ajout de l'élément au portfolio
        $this->photographerService->addPortfolioItem($photographerId, $request->all());

        return redirect()->route('traiteur.services.photographer.show', $photographerId)
            ->with('success', 'L\'élément a été ajouté au portfolio avec succès.');
    }

    /**
     * Supprime un élément du portfolio
     */
    public function destroyPortfolioItem($photographerId, $itemId)
    {
        $photographer = $this->photographerService->getPhotographerWithPortfolio($photographerId);

        // Vérifie que le photographe appartient au traiteur connecté
        if (!$this->photographerBelongsToTraiteur($photographer)) {
            return redirect()->route('traiteur.services.photographer.index')
                ->with('error', 'Vous n\'êtes pas autorisé à effectuer cette action.');
        }

        // Supprime l'élément du portfolio
        $this->photographerService->deletePortfolioItem($itemId);

        return redirect()->route('traiteur.services.photographer.show', $photographerId)
            ->with('success', 'L\'élément a été supprimé du portfolio avec succès.');
    }

    /**
     * Méthode auxiliaire pour vérifier si un photographe appartient au traiteur connecté
     */
    private function photographerBelongsToTraiteur($photographer)
    {
        $traiteurId = Auth::user()->traiteur->id;

        return $photographer->service->traiteur_id == $traiteurId;
    }
}
