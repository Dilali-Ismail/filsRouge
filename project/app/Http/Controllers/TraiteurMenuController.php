<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MenuService;
use App\Http\Requests\MenuRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\MenuItemRequest;

class TraiteurMenuController extends Controller
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
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
        $menus = $this->menuService->getAllMenusByTraiteur($traiteurId);

        // Si la requête est AJAX, retourne seulement la partie contenu
        if (request()->ajax()) {
            return view('traiteur.services.menus.index_content', compact('menus'));
        }

        // Récupère toutes les catégories de services pour le menu latéral
        $categories = DB::table('service_categories')->get();

        return view('traiteur.gerer_services', [
            'activeTab' => 'menu',
            'categories' => $categories,
            'contentView' => view('traiteur.services.menus.index_content', compact('menus'))->render()
        ]);
    }

    public function create()
    {
        return view('traiteur.services.menus.create');
    }

    public function store(MenuRequest $request)
    {
        $traiteurId = Auth::user()->traiteur->id;
        $menu = $this->menuService->createMenu($traiteurId, $request->validated());

        return redirect()->route('traiteur.services.menu.show', $menu->id)
            ->with('success', 'Le menu a été créé avec succès.');
    }

    public function show($id)
    {
        $menu = $this->menuService->getMenuWithItems($id);

        if (Auth::user()->traiteur->id != $menu->traiteur_id) {
            return redirect()->route('traiteur.services.menu.index')
                ->with('error', 'Vous n\'êtes pas autorisé à voir ce menu.');
        }

        return view('traiteur.services.menus.show', compact('menu'));
    }

    public function edit($id)
    {
        $menu = $this->menuService->getMenuWithItems($id);

        if (Auth::user()->traiteur->id != $menu->traiteur_id) {
            return redirect()->route('traiteur.services.menu.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier ce menu.');
        }

        return view('traiteur.services.menus.edit', compact('menu'));
    }

    public function update(MenuRequest $request, $id)
    {
        $menu = $this->menuService->getMenuWithItems($id);

        // Vérifie que le menu appartient bien au traiteur connecté
        if (Auth::user()->traiteur->id != $menu->traiteur_id) {
            return redirect()->route('traiteur.services.menu.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier ce menu.');
        }

        $this->menuService->updateMenu($id, $request->validated());

        return redirect()->route('traiteur.services.menu.show', $id)
            ->with('success', 'Le menu a été mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $menu = $this->menuService->getMenuWithItems($id);

        // Vérifie que le menu appartient bien au traiteur connecté
        if (Auth::user()->traiteur->id != $menu->traiteur_id) {
            return redirect()->route('traiteur.services.menu.index')
                ->with('error', 'Vous n\'êtes pas autorisé à supprimer ce menu.');
        }

        $this->menuService->deleteMenu($id);

        return redirect()->route('traiteur.services.menu.index')
            ->with('success', 'Le menu a été supprimé avec succès.');
    }

    public function createMenuItem($menuId)
    {
        $menu = $this->menuService->getMenuWithItems($menuId);

        // Vérifie que le menu appartient bien au traiteur connecté
        if (Auth::user()->traiteur->id != $menu->traiteur_id) {
            return redirect()->route('traiteur.services.menu.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier ce menu.');
        }

        return view('traiteur.services.menus.items.create', compact('menu'));
    }

    public function storeMenuItem(MenuItemRequest $request, $menuId)
    {
        $menu = $this->menuService->getMenuWithItems($menuId);

        // Vérifie que le menu appartient bien au traiteur connecté
        if (Auth::user()->traiteur->id != $menu->traiteur_id) {
            return redirect()->route('traiteur.services.menu.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier ce menu.');
        }

        $this->menuService->addMenuItem($menuId, $request->validated());

        return redirect()->route('traiteur.services.menu.show', $menuId)
            ->with('success', 'L\'item a été ajouté au menu avec succès.');
    }

    public function editMenuItem($menuId, $itemId)
    {
        $menu = $this->menuService->getMenuWithItems($menuId);

        // Vérifie que le menu appartient bien au traiteur connecté
        if (Auth::user()->traiteur->id != $menu->traiteur_id) {
            return redirect()->route('traiteur.services.menu.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier ce menu.');
        }

        $menuItem = $menu->menuItems->find($itemId);

        if (!$menuItem) {
            return redirect()->route('traiteur.services.menu.show', $menuId)
                ->with('error', 'L\'item demandé n\'existe pas.');
        }

        return view('traiteur.services.menus.items.edit', compact('menu', 'menuItem'));
    }

    public function updateMenuItem(MenuItemRequest $request, $menuId, $itemId)
    {
        $menu = $this->menuService->getMenuWithItems($menuId);

        // Vérifie que le menu appartient bien au traiteur connecté
        if (Auth::user()->traiteur->id != $menu->traiteur_id) {
            return redirect()->route('traiteur.services.menu.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier ce menu.');
        }

        $menuItem = $menu->menuItems->find($itemId);

        if (!$menuItem) {
            return redirect()->route('traiteur.services.menu.show', $menuId)
                ->with('error', 'L\'item demandé n\'existe pas.');
        }

        $this->menuService->updateMenuItem($itemId, $request->validated());

        return redirect()->route('traiteur.services.menu.show', $menuId)
            ->with('success', 'L\'item a été mis à jour avec succès.');
    }

    public function destroyMenuItem($menuId, $itemId)
    {
        $menu = $this->menuService->getMenuWithItems($menuId);

        // Vérifie que le menu appartient bien au traiteur connecté
        if (Auth::user()->traiteur->id != $menu->traiteur_id) {
            return redirect()->route('traiteur.services.menu.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier ce menu.');
        }

        $menuItem = $menu->menuItems->find($itemId);

        if (!$menuItem) {
            return redirect()->route('traiteur.services.menu.show', $menuId)
                ->with('error', 'L\'item demandé n\'existe pas.');
        }

        $this->menuService->deleteMenuItem($itemId);

        return redirect()->route('traiteur.services.menu.show', $menuId)
            ->with('success', 'L\'item a été supprimé avec succès.');
    }
}
