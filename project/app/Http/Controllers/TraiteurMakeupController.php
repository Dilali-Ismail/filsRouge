<?php

namespace App\Http\Controllers;

use App\Services\MakeupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TraiteurMakeupController extends Controller
{
    protected $makeupService;

    public function __construct(MakeupService $makeupService)
    {
        $this->makeupService = $makeupService;
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isTraiteur()) {
                return redirect()->route('welcome')->with('error', 'Accès non autorisé.');
            }
            return $next($request);
        });
    }

    /**
     * Affiche la liste des prestataires de maquillage
     */
    public function index()
    {
        $traiteurId = Auth::user()->traiteur->id;
        $makeupServices = $this->makeupService->getAllMakeupServicesByTraiteur($traiteurId);
        $makeups = [];

        foreach ($makeupServices as $service) {
            $serviceMakeups = $service->makeups;
            $makeups = array_merge($makeups, $serviceMakeups->all());
        }

        // Si la requête est AJAX, retourne seulement la partie contenu
        if (request()->ajax()) {
            return view('traiteur.services.maquillage.index_content', compact('makeups'));
        }

        // Récupère toutes les catégories de services pour le menu latéral
        $categories = DB::table('service_categories')->get();

        return view('traiteur.gerer_services', [
            'activeTab' => 'maquillage',
            'categories' => $categories,
            'contentView' => view('traiteur.services.maquillage.index_content', compact('makeups'))->render()
        ]);
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        return view('traiteur.services.maquillage.create');
    }

    /**
     * Enregistre un nouveau prestataire de maquillage
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

        // Récupère ou crée un service de type maquillage
        $makeupService = $this->makeupService->getOrCreateMakeupService($traiteurId);

        // Création du maquilleur/maquilleuse
        $makeup = $this->makeupService->createMakeup($makeupService->id, $request->all());

        // Redirection vers la liste des maquilleurs/maquilleuses (modifié selon votre demande)
        return redirect()->route('traiteur.services.maquillage.index')
            ->with('success', 'Le maquilleur/maquilleuse a été ajouté(e) avec succès.');
    }

    /**
     * Affiche les détails d'un prestataire de maquillage
     */
    public function show($id)
    {
        $makeup = $this->makeupService->getMakeupWithPortfolio($id);

        // Vérifie que le makeup appartient au traiteur connecté
        if (!$this->makeupBelongsToTraiteur($makeup)) {
            return redirect()->route('traiteur.services.maquillage.index')
                ->with('error', 'Vous n\'êtes pas autorisé à voir ce prestataire de maquillage.');
        }

        return view('traiteur.services.maquillage.show', compact('makeup'));
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit($id)
    {
        $makeup = $this->makeupService->getMakeupWithPortfolio($id);

        // Vérifie que le makeup appartient au traiteur connecté
        if (!$this->makeupBelongsToTraiteur($makeup)) {
            return redirect()->route('traiteur.services.maquillage.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier ce prestataire de maquillage.');
        }

        return view('traiteur.services.maquillage.edit', compact('makeup'));
    }

    /**
     * Met à jour un prestataire de maquillage
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

        $makeup = $this->makeupService->getMakeupWithPortfolio($id);

        // Vérifie que le makeup appartient au traiteur connecté
        if (!$this->makeupBelongsToTraiteur($makeup)) {
            return redirect()->route('traiteur.services.maquillage.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier ce prestataire de maquillage.');
        }

        // Mise à jour du maquilleur/maquilleuse
        $this->makeupService->updateMakeup($id, $request->all());

        return redirect()->route('traiteur.services.maquillage.index')
            ->with('success', 'Le maquilleur/maquilleuse a été mis(e) à jour avec succès.');
    }

    /**
     * Supprime un prestataire de maquillage
     */
    public function destroy($id)
    {
        $makeup = $this->makeupService->getMakeupWithPortfolio($id);

        // Vérifie que le makeup appartient au traiteur connecté
        if (!$this->makeupBelongsToTraiteur($makeup)) {
            return redirect()->route('traiteur.services.maquillage.index')
                ->with('error', 'Vous n\'êtes pas autorisé à supprimer ce prestataire de maquillage.');
        }

        // Supprime le maquilleur/maquilleuse
        $this->makeupService->deleteMakeup($id);

        return redirect()->route('traiteur.services.maquillage.index')
            ->with('success', 'Le maquilleur/maquilleuse a été supprimé(e) avec succès.');
    }

    /**
     * Affiche le formulaire d'ajout d'élément au portfolio
     */
    public function createPortfolioItem($makeupId)
    {
        $makeup = $this->makeupService->getMakeupWithPortfolio($makeupId);

        // Vérifie que le makeup appartient au traiteur connecté
        if (!$this->makeupBelongsToTraiteur($makeup)) {
            return redirect()->route('traiteur.services.maquillage.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier ce prestataire de maquillage.');
        }

        return view('traiteur.services.maquillage.portfolio.create', compact('makeup'));
    }

    /**
     * Enregistre un nouvel élément au portfolio
     */
    public function storePortfolioItem(Request $request, $makeupId)
    {
        $request->validate([
            'type' => 'required|in:image,video',
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:20480',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $makeup = $this->makeupService->getMakeupWithPortfolio($makeupId);

        // Vérifie que le makeup appartient au traiteur connecté
        if (!$this->makeupBelongsToTraiteur($makeup)) {
            return redirect()->route('traiteur.services.maquillage.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier ce prestataire de maquillage.');
        }

        // Ajout de l'élément au portfolio
        $this->makeupService->addPortfolioItem($makeupId, $request->all());

        return redirect()->route('traiteur.services.maquillage.show', $makeupId)
            ->with('success', 'L\'élément a été ajouté au portfolio avec succès.');
    }

    /**
     * Supprime un élément du portfolio
     */
    public function destroyPortfolioItem($makeupId, $itemId)
    {
        $makeup = $this->makeupService->getMakeupWithPortfolio($makeupId);

        // Vérifie que le makeup appartient au traiteur connecté
        if (!$this->makeupBelongsToTraiteur($makeup)) {
            return redirect()->route('traiteur.services.maquillage.index')
                ->with('error', 'Vous n\'êtes pas autorisé à effectuer cette action.');
        }

        // Supprime l'élément du portfolio
        $this->makeupService->deletePortfolioItem($itemId);

        return redirect()->route('traiteur.services.maquillage.show', $makeupId)
            ->with('success', 'L\'élément a été supprimé du portfolio avec succès.');
    }

    /**
     * Méthode auxiliaire pour vérifier si un makeup appartient au traiteur connecté
     */
    private function makeupBelongsToTraiteur($makeup)
    {
        $traiteurId = Auth::user()->traiteur->id;

        return $makeup->service->traiteur_id == $traiteurId;
    }
}
