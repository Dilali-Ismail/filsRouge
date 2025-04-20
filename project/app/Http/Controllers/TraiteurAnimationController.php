<?php

namespace App\Http\Controllers;

use App\Services\AnimationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TraiteurAnimationController extends Controller
{
    protected $animationService;

    public function __construct(AnimationService $animationService)
    {
        //DI
        $this->animationService = $animationService;
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
        $animationServices = $this->animationService->getAllAnimationServicesByTraiteur($traiteurId);
        $animations = [];

        foreach ($animationServices as $service) {
            $serviceAnimations = $service->animations;
            $animations = array_merge($animations, $serviceAnimations->all());
        }

        // Si la requête est AJAX, retourne seulement la partie contenu
        if (request()->ajax()) {
            return view('traiteur.services.animation.index_content', compact('animations'));
        }

        // Récupère toutes les catégories de services pour le menu latéral
        $categories = DB::table('service_categories')->get();

        return view('traiteur.gerer_services', [
            'activeTab' => 'animation',
            'categories' => $categories,
            'contentView' => view('traiteur.services.animation.index_content', compact('animations'))->render()
        ]);
    }

    /**
     * Affiche le formulaire de création d'une animation
     */
    public function create()
    {
        return view('traiteur.services.animation.create');
    }

    /**
     * Enregistre une nouvelle animation
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|in:chaabi,dakka,andalouse,orchestre',
        ]);

        $traiteurId = Auth::user()->traiteur->id;

        // Récupère ou crée un service de type animation
        $animationService = $this->animationService->getOrCreateAnimationService($traiteurId);

        // Création de l'animation
        $animation = $this->animationService->createAnimation($animationService->id, $request->all());

        return redirect()->route('traiteur.services.animation.index')
            ->with('success', 'L\'animation a été ajoutée avec succès.');
    }

    /**
     * Affiche les détails d'une animation
     */
    public function show($id)
    {
        $animation = $this->animationService->getAnimation($id);

        // Vérifie que l'animation appartient au traiteur connecté
        if (!$this->animationBelongsToTraiteur($animation)) {
            return redirect()->route('traiteur.services.animation.index')
                ->with('error', 'Vous n\'êtes pas autorisé à voir cette animation.');
        }

        return view('traiteur.services.animation.show', compact('animation'));
    }

    /**
     * Affiche le formulaire d'édition d'une animation
     */
    public function edit($id)
    {
        $animation = $this->animationService->getAnimation($id);

        // Vérifie que l'animation appartient au traiteur connecté
        if (!$this->animationBelongsToTraiteur($animation)) {
            return redirect()->route('traiteur.services.animation.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette animation.');
        }

        return view('traiteur.services.animation.edit', compact('animation'));
    }

    /**
     * Met à jour une animation
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|in:chaabi,dakka,andalouse,orchestre',
        ]);

        $animation = $this->animationService->getAnimation($id);

        // Vérifie que l'animation appartient au traiteur connecté
        if (!$this->animationBelongsToTraiteur($animation)) {
            return redirect()->route('traiteur.services.animation.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette animation.');
        }

        // Mise à jour de l'animation
        $this->animationService->updateAnimation($id, $request->all());

        return redirect()->route('traiteur.services.animation.index')
            ->with('success', 'L\'animation a été mise à jour avec succès.');
    }

    /**
     * Supprime une animation
     */
    public function destroy($id)
    {
        $animation = $this->animationService->getAnimation($id);

        // Vérifie que l'animation appartient au traiteur connecté
        if (!$this->animationBelongsToTraiteur($animation)) {
            return redirect()->route('traiteur.services.animation.index')
                ->with('error', 'Vous n\'êtes pas autorisé à supprimer cette animation.');
        }

        // Supprime l'animation
        $this->animationService->deleteAnimation($id);

        return redirect()->route('traiteur.services.animation.index')
            ->with('success', 'L\'animation a été supprimée avec succès.');
    }

    /**
     * Méthode auxiliaire pour vérifier si une animation appartient au traiteur connecté
     */
    private function animationBelongsToTraiteur($animation)
    {
        $traiteurId = Auth::user()->traiteur->id;
        return $animation->service->traiteur_id == $traiteurId;
    }
}
