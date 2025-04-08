<?php

namespace App\Services;

use App\Repositories\Interfaces\ServiceRepositoryInterface;
use App\Repositories\Interfaces\MenuItemRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MenuService
{
    protected $serviceRepository;
    protected $menuItemRepository;

    public function __construct(
        ServiceRepositoryInterface $serviceRepository,
        MenuItemRepositoryInterface $menuItemRepository
    ) {
        $this->serviceRepository = $serviceRepository;
        $this->menuItemRepository = $menuItemRepository;
    }

    /**
     * Récupère tous les menus d'un traiteur.
     */
    public function getAllMenusByTraiteur($traiteurId)
    {
        // Récupère l'ID de la catégorie 'menu'
        $menuCategoryId = DB::table('service_categories')->where('name', 'menu')->first()->id;

        // Récupère tous les services de type 'menu' pour ce traiteur
        return $this->serviceRepository->getByTraiteurAndCategory($traiteurId, $menuCategoryId);
    }

    /**
     * Récupère un menu avec tous ses items.
     */
    public function getMenuWithItems($menuId)
    {
        $menu = $this->serviceRepository->find($menuId);
        $menu->load('menuItems');
        return $menu;
    }

    /**
     * Crée un nouveau menu.
     */
    public function createMenu($traiteurId, array $data)
    {
        // Récupère l'ID de la catégorie 'menu'
        $menuCategoryId = DB::table('service_categories')->where('name', 'menu')->first()->id;

        // Prépare les données pour le service
        $serviceData = [
            'traiteur_id' => $traiteurId,
            'category_id' => $menuCategoryId,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
        ];

        // Crée le service de type menu
        return $this->serviceRepository->create($serviceData);
    }

    /**
     * Met à jour un menu existant.
     */
    public function updateMenu($menuId, array $data)
    {
        // Prépare les données pour le service
        $serviceData = [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
        ];

        // Met à jour le service
        return $this->serviceRepository->update($menuId, $serviceData);
    }

    /**
     * Supprime un menu et tous ses items.
     */
    public function deleteMenu($menuId)
    {
        // Les items de menu seront supprimés automatiquement grâce à la relation onDelete('cascade')
        return $this->serviceRepository->delete($menuId);
    }

    /**
     * Ajoute un nouvel item à un menu.
     */
    public function addMenuItem($menuId, array $data)
    {
        // Gestion de l'upload de photo si présent
        $photoPath = null;
        if (isset($data['photo']) && $data['photo']) {
            $photoPath = $data['photo']->store('menu-items', 'public');
        }

        // Prépare les données pour l'item de menu
        $itemData = [
            'service_id' => $menuId,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'photo' => $photoPath,
            'category' => $data['category'],
        ];

        // Crée l'item de menu
        return $this->menuItemRepository->create($itemData);
    }

    /**
     * Met à jour un item de menu existant.
     */
    public function updateMenuItem($itemId, array $data)
    {
        $menuItem = $this->menuItemRepository->find($itemId);

        // Gestion de l'upload de photo si présent
        if (isset($data['photo']) && $data['photo']) {
            // Supprime l'ancienne photo si elle existe
            if ($menuItem->photo) {
                Storage::disk('public')->delete($menuItem->photo);
            }

            $data['photo'] = $data['photo']->store('menu-items', 'public');
        } else {
            // Si pas de nouvelle photo, conserve l'ancienne
            unset($data['photo']);
        }

        // Met à jour l'item
        return $this->menuItemRepository->update($itemId, $data);
    }

    /**
     * Supprime un item de menu.
     */
    public function deleteMenuItem($itemId)
    {
        $menuItem = $this->menuItemRepository->find($itemId);

        // Supprime la photo si elle existe
        if ($menuItem->photo) {
            Storage::disk('public')->delete($menuItem->photo);
        }

        // Supprime l'item
        return $this->menuItemRepository->delete($itemId);
    }
}
