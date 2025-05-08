<?php

namespace App\Services;

use App\Models\ServiceCategory;
use App\Repositories\Interfaces\MakeupRepositoryInterface;
use App\Repositories\Interfaces\ServiceRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MakeupService
{
    protected $makeupRepository;
    protected $serviceRepository;

    public function __construct(
        MakeupRepositoryInterface $makeupRepository,
        ServiceRepositoryInterface $serviceRepository
    ) {
        $this->makeupRepository = $makeupRepository;
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * Récupère tous les services de maquillage d'un traiteur.
     */
    public function getAllMakeupServicesByTraiteur($traiteurId)
    {
        // Récupère l'ID de la catégorie 'maquillage'
        $makeupCategoryId = DB::table('service_categories')->where('name', 'maquillage')->first()->id;

        // Récupère tous les services de type 'maquillage' pour ce traiteur
        return $this->serviceRepository->getByTraiteurAndCategory($traiteurId, $makeupCategoryId);
    }

    /**
     * Récupère ou crée un service de type maquillage.
     */
    public function getOrCreateMakeupService($traiteurId)
    {
        // Récupère l'ID de la catégorie 'maquillage'
        $makeupCategoryId = DB::table('service_categories')->where('name', 'maquillage')->first()->id;

        $services = $this->serviceRepository->getByTraiteurAndCategory($traiteurId, $makeupCategoryId);

        if ($services->isEmpty()) {
            // Crée un nouveau service de type maquillage
            return $this->serviceRepository->create([
                'traiteur_id' => $traiteurId,
                'category_id' => $makeupCategoryId,
                'title' => 'Service de Maquillage',
                'description' => 'Services de maquillage proposés par notre établissement',
            ]);
        }

        // Retourne le premier service existant
        return $services->first();
    }

    /**
     * Récupère un service de maquillage avec ses éléments de portfolio.
     */
    public function getMakeupWithPortfolio($id)
    {
        return $this->makeupRepository->find($id);
    }

    /**
     * Crée un nouveau service de maquillage.
     */
    public function createMakeup($serviceId, array $data)
    {
        if (isset($data['photo']) && $data['photo']) {
            $data['photo'] = $data['photo']->store('makeups', 'public');
        }

        $data['service_id'] = $serviceId;

        return $this->makeupRepository->create($data);
    }

    
public function updateMakeup($id, array $data)
{
    $makeup = $this->makeupRepository->find($id);

    // Gestion de la suppression de photo existante
    if (isset($data['remove_photo']) && $data['remove_photo'] == 1) {
        if ($makeup->photo) {
            Storage::disk('public')->delete($makeup->photo);
        }
        $data['photo'] = null;
    }
    // Gestion de l'upload d'une nouvelle photo
    elseif (isset($data['photo']) && $data['photo']) {
        // Supprime l'ancienne photo si elle existe
        if ($makeup->photo) {
            Storage::disk('public')->delete($makeup->photo);
        }

        $data['photo'] = $data['photo']->store('makeups', 'public');
    }

    // Dans tous les cas, on retire ces clés du tableau pour éviter des erreurs
    unset($data['remove_photo']);
    if (!isset($data['photo']) || !$data['photo']) {
        unset($data['photo']);
    }

    return $this->makeupRepository->update($id, $data);
}

    /**
     * Supprime un service de maquillage.
     */
    public function deleteMakeup($id)
    {
        return $this->makeupRepository->delete($id);
    }

    /**
     * Ajoute un élément au portfolio.
     */
    public function addPortfolioItem($makeupId, array $data)
    {
        if (isset($data['file']) && $data['file']) {
            $data['file_path'] = $data['file']->store('makeup-portfolio', 'public');
            unset($data['file']);
        }

        return $this->makeupRepository->addPortfolioItem($makeupId, $data);
    }

    /**
     * Supprime un élément du portfolio.
     */
    public function deletePortfolioItem($itemId)
    {
        return $this->makeupRepository->deletePortfolioItem($itemId);
    }
}
