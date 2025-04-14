<?php

namespace App\Http\Controllers;

use App\Services\AmariyaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TraiteurAmariyaController extends Controller
{
    protected $amariyaService;

    public function __construct(AmariyaService $amariyaService)
    {
        $this->amariyaService = $amariyaService;
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isTraiteur()) {
                return redirect()->route('welcome')->with('error', 'Accès non autorisé.');
            }
            return $next($request);
        });
    }

    /**
     * Affiche la liste des amariyas
     */
    public function index()
    {
        $traiteurId = Auth::user()->traiteur->id;
        $amariyaServices = $this->amariyaService->getAllAmariyaServicesByTraiteur($traiteurId);
        $amariyas = [];

        foreach ($amariyaServices as $service) {
            $serviceAmariyas = $service->amariyas;
            $amariyas = array_merge($amariyas, $serviceAmariyas->all());
        }

        // Si la requête est AJAX, retourne seulement la partie contenu
        if (request()->ajax()) {
            return view('traiteur.services.amariya.index_content', compact('amariyas'));
        }

        // Récupère toutes les catégories de services pour le menu latéral
        $categories = DB::table('service_categories')->get();

        return view('traiteur.gerer_services', [
            'activeTab' => 'amariya',
            'categories' => $categories,
            'contentView' => view('traiteur.services.amariya.index_content', compact('amariyas'))->render()
        ]);
    }

    /**
     * Affiche le formulaire de création d'une amariya
     */
    public function create()
    {
        return view('traiteur.services.amariya.create');
    }

    /**
     * Enregistre une nouvelle amariya
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

        // Récupère ou crée un service de type amariya
        $amariyaService = $this->amariyaService->getOrCreateAmariyaService($traiteurId);

        // Création de l'amariya
        $amariya = $this->amariyaService->createAmariya($amariyaService->id, $request->all());

        return redirect()->route('traiteur.services.amariya.index')
            ->with('success', 'L\'amariya a été ajoutée avec succès.');
    }

    /**
     * Affiche les détails d'une amariya
     */
    public function show($id)
    {
        $amariya = $this->amariyaService->getAmariya($id);

        // Vérifie que l'amariya appartient au traiteur connecté
        if (!$this->amariyaBelongsToTraiteur($amariya)) {
            return redirect()->route('traiteur.services.amariya.index')
                ->with('error', 'Vous n\'êtes pas autorisé à voir cette amariya.');
        }

        return view('traiteur.services.amariya.show', compact('amariya'));
    }

    /**
     * Affiche le formulaire d'édition d'une amariya
     */
    public function edit($id)
    {
        $amariya = $this->amariyaService->getAmariya($id);

        // Vérifie que l'amariya appartient au traiteur connecté
        if (!$this->amariyaBelongsToTraiteur($amariya)) {
            return redirect()->route('traiteur.services.amariya.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette amariya.');
        }

        return view('traiteur.services.amariya.edit', compact('amariya'));
    }

    /**
     * Met à jour une amariya
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $amariya = $this->amariyaService->getAmariya($id);

        // Vérifie que l'amariya appartient au traiteur connecté
        if (!$this->amariyaBelongsToTraiteur($amariya)) {
            return redirect()->route('traiteur.services.amariya.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette amariya.');
        }

        // Mise à jour de l'amariya
        $this->amariyaService->updateAmariya($id, $request->all());

        return redirect()->route('traiteur.services.amariya.index')
            ->with('success', 'L\'amariya a été mise à jour avec succès.');
    }

    /**
     * Supprime une amariya
     */
    public function destroy($id)
    {
        $amariya = $this->amariyaService->getAmariya($id);

        // Vérifie que l'amariya appartient au traiteur connecté
        if (!$this->amariyaBelongsToTraiteur($amariya)) {
            return redirect()->route('traiteur.services.amariya.index')
                ->with('error', 'Vous n\'êtes pas autorisé à supprimer cette amariya.');
        }

        // Supprime l'amariya
        $this->amariyaService->deleteAmariya($id);

        return redirect()->route('traiteur.services.amariya.index')
            ->with('success', 'L\'amariya a été supprimée avec succès.');
    }

    /**
     * Méthode auxiliaire pour vérifier si une amariya appartient au traiteur connecté
     */
    private function amariyaBelongsToTraiteur($amariya)
    {
        $traiteurId = Auth::user()->traiteur->id;
        return $amariya->service->traiteur_id == $traiteurId;
    }
}
