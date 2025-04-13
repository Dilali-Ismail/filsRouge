<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ClothingService;
use App\Http\Requests\ClothingServiceRequest;
use App\Http\Requests\ClothingItemRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TraiteurClothingController extends Controller
{
    protected $clothingService;

    public function __construct(ClothingService $clothingService)
    {
        $this->clothingService = $clothingService;
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
        $clothingServices = $this->clothingService->getAllClothingServicesByTraiteur($traiteurId);

        // Si la requête est AJAX, retourne seulement la partie contenu
        if (request()->ajax()) {
            return view('traiteur.services.vetements.index_content', compact('clothingServices'));
        }

        // Récupère toutes les catégories de services pour le menu latéral
        $categories = DB::table('service_categories')->get();

        return view('traiteur.gerer_services', [
            'activeTab' => 'vetements',
            'categories' => $categories,
            'contentView' => view('traiteur.services.vetements.index_content', compact('clothingServices'))->render()
        ]);
    }

    public function create()
    {
        return view('traiteur.services.vetements.create');
    }

    public function store(ClothingServiceRequest $request)
    {
        $traiteurId = Auth::user()->traiteur->id;
        $service = $this->clothingService->createClothingService($traiteurId, $request->validated());

        return redirect()->route('traiteur.services.negafa.index')
        ->with('success', 'La négafa a été ajoutée avec succès.');
    }

    public function show($id)
    {
        $service = $this->clothingService->getClothingServiceWithItems($id);

        if (Auth::user()->traiteur->id != $service->traiteur_id) {
            return redirect()->route('traiteur.services.vetements.index')
                ->with('error', 'Vous n\'êtes pas autorisé à voir ce service.');
        }

        return view('traiteur.services.vetements.show', compact('service'));
    }

    public function edit($id)
    {
        $service = $this->clothingService->getClothingServiceWithItems($id);

        if (Auth::user()->traiteur->id != $service->traiteur_id) {
            return redirect()->route('traiteur.services.vetements.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier ce service.');
        }

        return view('traiteur.services.vetements.edit', compact('service'));
    }

    public function update(ClothingServiceRequest $request, $id)
    {
        $service = $this->clothingService->getClothingServiceWithItems($id);

        // Vérifie que le service appartient bien au traiteur connecté
        if (Auth::user()->traiteur->id != $service->traiteur_id) {
            return redirect()->route('traiteur.services.vetements.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier ce service.');
        }

        $this->clothingService->updateClothingService($id, $request->validated());

        return redirect()->route('traiteur.services.vetements.show', $id)
            ->with('success', 'Le service de vêtements a été mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $service = $this->clothingService->getClothingServiceWithItems($id);

        // Vérifie que le service appartient bien au traiteur connecté
        if (Auth::user()->traiteur->id != $service->traiteur_id) {
            return redirect()->route('traiteur.services.vetements.index')
                ->with('error', 'Vous n\'êtes pas autorisé à supprimer ce service.');
        }

        $this->clothingService->deleteClothingService($id);

        return redirect()->route('traiteur.services.vetements.index')
            ->with('success', 'Le service de vêtements a été supprimé avec succès.');
    }

    public function createClothingItem($serviceId)
    {
        $service = $this->clothingService->getClothingServiceWithItems($serviceId);

        // Vérifie que le service appartient bien au traiteur connecté
        if (Auth::user()->traiteur->id != $service->traiteur_id) {
            return redirect()->route('traiteur.services.vetements.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier ce service.');
        }

        return view('traiteur.services.vetements.items.create', compact('service'));
    }

    // Méthode modifiée pour corriger le problème d'enregistrement
    public function storeClothingItem(ClothingItemRequest $request, $style)
    {
        $traiteurId = Auth::user()->traiteur->id;

        // Obtenir tous les services de vêtements du traiteur
        $clothingServices = $this->clothingService->getAllClothingServicesByTraiteur($traiteurId);

        // Si aucun service n'existe, en créer un
        if ($clothingServices->isEmpty()) {
            $serviceData = [
                'title' => 'Vêtements ' . ($style == 'traditionnel' ? 'Traditionnels' : 'Modernes'),
                'description' => 'Collection de vêtements ' . ($style == 'traditionnel' ? 'traditionnels' : 'modernes')
            ];
            $service = $this->clothingService->createClothingService($traiteurId, $serviceData);
        } else {
            // Utiliser le premier service existant
            $service = $clothingServices->first();
        }

        // Récupérer les données validées
        $data = $request->validated();

        // S'assurer que le style est correctement défini
        $data['style'] = $style;

        // Ajouter l'item au service
        $this->clothingService->addClothingItem($service->id, $data);

        return redirect()->back()->with('success', 'Le vêtement a été ajouté avec succès.');
    }

    public function editClothingItem($style, $itemId)
{
    $clothingItem = $this->clothingService->getClothingItem($itemId);

    if (!$clothingItem) {
        return redirect()->route('traiteur.services.vetements.' . ($style == 'moderne' ? 'modern' : 'traditional'))
            ->with('error', 'Le vêtement demandé n\'existe pas.');
    }

    // Vérifier que l'item appartient au traiteur connecté
    $service = $clothingItem->service;
    if (Auth::user()->traiteur->id != $service->traiteur_id) {
        return redirect()->route('traiteur.services.vetements.' . ($style == 'moderne' ? 'modern' : 'traditional'))
            ->with('error', 'Vous n\'êtes pas autorisé à modifier ce vêtement.');
    }

    return view('traiteur.services.vetements.items.edit', compact('clothingItem', 'style'));
}

public function updateClothingItem(ClothingItemRequest $request, $style, $itemId)
{
    $clothingItem = $this->clothingService->getClothingItem($itemId);

    if (!$clothingItem) {
        return redirect()->route('traiteur.services.vetements.' . ($style == 'moderne' ? 'modern' : 'traditional'))
            ->with('error', 'Le vêtement demandé n\'existe pas.');
    }

    // Vérifier que l'item appartient au traiteur connecté
    $service = $clothingItem->service;
    if (Auth::user()->traiteur->id != $service->traiteur_id) {
        return redirect()->route('traiteur.services.vetements.' . ($style == 'moderne' ? 'modern' : 'traditional'))
            ->with('error', 'Vous n\'êtes pas autorisé à modifier ce vêtement.');
    }

    // Récupérer les données validées et conserver le style
    $data = $request->validated();
    $data['style'] = $style;

    $this->clothingService->updateClothingItem($itemId, $data);

    return redirect()->route('traiteur.services.vetements.' . ($style == 'moderne' ? 'modern' : 'traditional'))
        ->with('success', 'Le vêtement a été mis à jour avec succès.');
}

    public function destroyClothingItem($style, $itemId)
{
    $clothingItem = $this->clothingService->getClothingItem($itemId);

    if (!$clothingItem) {
        return redirect()->route('traiteur.services.vetements.' . ($style == 'moderne' ? 'modern' : 'traditional'))
            ->with('error', 'Le vêtement demandé n\'existe pas.');
    }

    // Vérifier que l'item appartient au traiteur connecté
    $service = $clothingItem->service;
    if (Auth::user()->traiteur->id != $service->traiteur_id) {
        return redirect()->route('traiteur.services.vetements.' . ($style == 'moderne' ? 'modern' : 'traditional'))
            ->with('error', 'Vous n\'êtes pas autorisé à modifier ce vêtement.');
    }

    $this->clothingService->deleteClothingItem($itemId);

    return redirect()->route('traiteur.services.vetements.' . ($style == 'moderne' ? 'modern' : 'traditional'))
        ->with('success', 'Le vêtement a été supprimé avec succès.');
}

    // Ajoutez ces méthodes

    public function showTraditional()
    {
        $traiteurId = Auth::user()->traiteur->id;
        $clothingServices = $this->clothingService->getAllClothingServicesByTraiteur($traiteurId);
        $clothingItems = [];
        $style = 'traditionnel';

        foreach ($clothingServices as $service) {
            // Assurez-vous que la relation clothingItems est bien définie
            $items = $service->clothing->where('style', 'traditionnel')->all();
            $clothingItems = array_merge($clothingItems, $items);
        }

        return view('traiteur.services.vetements.traditional', compact('clothingItems', 'style'));
    }

    public function showModern()
    {
        $traiteurId = Auth::user()->traiteur->id;
        $clothingServices = $this->clothingService->getAllClothingServicesByTraiteur($traiteurId);
        $clothingItems = [];
        $style = 'moderne';

        foreach ($clothingServices as $service) {
            // Assurez-vous que la relation clothingItems est bien définie
            $items = $service->clothing->where('style', 'moderne')->all();
            $clothingItems = array_merge($clothingItems, $items);
        }

        return view('traiteur.services.vetements.modern', compact('clothingItems', 'style'));
    }
}
