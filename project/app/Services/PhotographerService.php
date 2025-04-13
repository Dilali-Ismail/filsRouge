<?php

namespace App\Services;

use App\Models\ServiceCategory;
use App\Repositories\Interfaces\PhotographerRepositoryInterface;
use App\Repositories\Interfaces\ServiceRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PhotographerService
{
    protected $photographerRepository;
    protected $serviceRepository;

    public function __construct(
        PhotographerRepositoryInterface $photographerRepository,
        ServiceRepositoryInterface $serviceRepository
    ) {
        $this->photographerRepository = $photographerRepository;
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * Récupère tous les services de photographe d'un traiteur.
     */
    public function getAllPhotographerServicesByTraiteur($traiteurId)
    {
        // Récupère l'ID de la catégorie 'photographer'
        $photographerCategoryId = DB::table('service_categories')->where('name', 'photographer')->first()->id;

        // Récupère tous les services de type 'photographer' pour ce traiteur
        return $this->serviceRepository->getByTraiteurAndCategory($traiteurId, $photographerCategoryId);
    }

    /**
     * Récupère ou crée un service de type photographe.
     */
    public function getOrCreatePhotographerService($traiteurId)
    {
        // Récupère l'ID de la catégorie 'photographer'
        $photographerCategoryId = DB::table('service_categories')->where('name', 'photographer')->first()->id;

        $services = $this->serviceRepository->getByTraiteurAndCategory($traiteurId, $photographerCategoryId);

        if ($services->isEmpty()) {
            // Crée un nouveau service de type photographe
            return $this->serviceRepository->create([
                'traiteur_id' => $traiteurId,
                'category_id' => $photographerCategoryId,
                'title' => 'Service de Photographe',
                'description' => 'Services de photographie proposés par notre établissement',
            ]);
        }

        // Retourne le premier service existant
        return $services->first();
    }

    /**
     * Récupère un service de photographe avec ses éléments de portfolio.
     */
    public function getPhotographerWithPortfolio($id)
    {
        return $this->photographerRepository->find($id);
    }

    /**
     * Crée un nouveau service de photographe.
     */
    public function createPhotographer($serviceId, array $data)
    {
        if (isset($data['photo']) && $data['photo']) {
            $data['photo'] = $data['photo']->store('photographers', 'public');
        }

        $data['service_id'] = $serviceId;

        return $this->photographerRepository->create($data);
    }

    /**
     * Met à jour un service de photographe.
     */
    public function updatePhotographer($id, array $data)
    {
        $photographer = $this->photographerRepository->find($id);

        if (isset($data['photo']) && $data['photo']) {
            // Supprime l'ancienne photo si elle existe
            if ($photographer->photo) {
                Storage::disk('public')->delete($photographer->photo);
            }

            $data['photo'] = $data['photo']->store('photographers', 'public');
        } else {
            // Si pas de nouvelle photo, conserve l'ancienne
            unset($data['photo']);
        }

        return $this->photographerRepository->update($id, $data);
    }

    /**
     * Supprime un service de photographe.
     */
    public function deletePhotographer($id)
    {
        return $this->photographerRepository->delete($id);
    }

    /**
     * Ajoute un élément au portfolio.
     */
    public function addPortfolioItem($photographerId, array $data)
    {
        if (isset($data['file']) && $data['file']) {
            $data['file_path'] = $data['file']->store('photographer-portfolio', 'public');
            unset($data['file']);
        }

        return $this->photographerRepository->addPortfolioItem($photographerId, $data);
    }

    /**
     * Supprime un élément du portfolio.
     */
    public function deletePortfolioItem($itemId)
    {
        return $this->photographerRepository->deletePortfolioItem($itemId);
    }
}
