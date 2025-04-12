<?php

namespace App\Services;

use App\Repositories\Interfaces\ServiceRepositoryInterface;
use App\Repositories\Interfaces\ClothingRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ClothingService
{
    protected $serviceRepository;
    protected $clothingItemRepository;

    public function __construct(
        ServiceRepositoryInterface $serviceRepository,
        ClothingRepositoryInterface $clothingItemRepository
    ) {
        $this->serviceRepository = $serviceRepository;
        $this->clothingItemRepository = $clothingItemRepository;
    }

    /**
     * Récupère tous les services de vêtements d'un traiteur.
     */
    public function getAllClothingServicesByTraiteur($traiteurId)
    {
        // Récupère l'ID de la catégorie 'vetements'
        $clothingCategoryId = DB::table('service_categories')->where('name', 'vetements')->first()->id;

        // Récupère tous les services de type 'vetements' pour ce traiteur
        return $this->serviceRepository->getByTraiteurAndCategory($traiteurId, $clothingCategoryId);
    }

    /**
     * Récupère un service de vêtements avec tous ses items.
     */
    public function getClothingServiceWithItems($serviceId)
    {
        $service = $this->serviceRepository->find($serviceId);
        $service->load('clothing');
        return $service;
    }

    /**
     * Récupère un item de vêtement par son ID.
     */
    public function getClothingItem($itemId)
    {
        return $this->clothingItemRepository->find($itemId);
    }

    /**
     * Crée un nouveau service de vêtements.
     */
    public function createClothingService($traiteurId, array $data)
    {
        // Récupère l'ID de la catégorie 'vetements'
        $clothingCategoryId = DB::table('service_categories')->where('name', 'vetements')->first()->id;

        // Prépare les données pour le service
        $serviceData = [
            'traiteur_id' => $traiteurId,
            'category_id' => $clothingCategoryId,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
        ];

        // Crée le service de type vêtements
        return $this->serviceRepository->create($serviceData);
    }

    /**
     * Met à jour un service de vêtements existant.
     */
    public function updateClothingService($serviceId, array $data)
    {
        // Prépare les données pour le service
        $serviceData = [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
        ];

        // Met à jour le service
        return $this->serviceRepository->update($serviceId, $serviceData);
    }

    /**
     * Supprime un service de vêtements et tous ses items.
     */
    public function deleteClothingService($serviceId)
    {
        // Les items de vêtements seront supprimés automatiquement grâce à la relation onDelete('cascade')
        return $this->serviceRepository->delete($serviceId);
    }

    /**
     * Ajoute un nouvel item de vêtement à un service.
     */
    public function addClothingItem($serviceId, array $data)
    {
        // Gestion de l'upload de photo si présent
        $photoPath = null;
        if (isset($data['photo']) && $data['photo']) {
            $photoPath = $data['photo']->store('clothing-items', 'public');
        }

        // Prépare les données pour l'item de vêtement
        $itemData = [
            'service_id' => $serviceId,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'photo' => $photoPath,
            'category' => $data['category'],
            'style' => $data['style'],
        ];

        // Crée l'item de vêtement
        return $this->clothingItemRepository->create($itemData);
    }

    /**
     * Met à jour un item de vêtement existant.
     */
    public function updateClothingItem($itemId, array $data)
    {
        $clothingItem = $this->clothingItemRepository->find($itemId);

        // Gestion de l'upload de photo si présent
        if (isset($data['photo']) && $data['photo']) {
            // Supprime l'ancienne photo si elle existe
            if ($clothingItem->photo) {
                Storage::disk('public')->delete($clothingItem->photo);
            }

            $data['photo'] = $data['photo']->store('clothing-items', 'public');
        } else {
            // Si pas de nouvelle photo, conserve l'ancienne
            unset($data['photo']);
        }

        // Met à jour l'item
        return $this->clothingItemRepository->update($itemId, $data);
    }

    /**
     * Supprime un item de vêtement.
     */
    public function deleteClothingItem($itemId)
    {
        $clothingItem = $this->clothingItemRepository->find($itemId);

        // Supprime la photo si elle existe
        if ($clothingItem->photo) {
            Storage::disk('public')->delete($clothingItem->photo);
        }

        // Supprime l'item
        return $this->clothingItemRepository->delete($itemId);
    }
}
