<?php

namespace App\Services;

use App\Models\ServiceCategory;
use App\Repositories\Interfaces\NegafaRepositoryInterface;
use App\Repositories\Interfaces\ServiceRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class NegafaService
{
    protected $negafaRepository;
    protected $serviceRepository;

    public function __construct(
        NegafaRepositoryInterface $negafaRepository,
        ServiceRepositoryInterface $serviceRepository
    ) {
        $this->negafaRepository = $negafaRepository;
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * Récupère tous les services de négafas d'un traiteur.
     */
    public function getAllNegafaServicesByTraiteur($traiteurId)
    {
        // Récupère l'ID de la catégorie 'negafa'
        $negafaCategoryId = DB::table('service_categories')->where('name', 'negafa')->first()->id;

        // Récupère tous les services de type 'negafa' pour ce traiteur
        return $this->serviceRepository->getByTraiteurAndCategory($traiteurId, $negafaCategoryId);
    }

    /**
     * Récupère ou crée un service de type négafa.
     */
    public function getOrCreateNegafaService($traiteurId)
    {
        // Récupère l'ID de la catégorie 'negafa'
        $negafaCategoryId = DB::table('service_categories')->where('name', 'negafa')->first()->id;

        $services = $this->serviceRepository->getByTraiteurAndCategory($traiteurId, $negafaCategoryId);

        if ($services->isEmpty()) {
            // Crée un nouveau service de type négafa
            return $this->serviceRepository->create([
                'traiteur_id' => $traiteurId,
                'category_id' => $negafaCategoryId,
                'title' => 'Service de Négafa',
                'description' => 'Services de négafa proposés par notre établissement',
            ]);
        }

        // Retourne le premier service existant
        return $services->first();
    }

    /**
     * Récupère une négafa avec ses éléments de portfolio.
     */
    public function getNegafaWithPortfolio($id)
    {
        return $this->negafaRepository->find($id);
    }

    /**
     * Crée une nouvelle négafa.
     */
    public function createNegafa($serviceId, array $data)
    {
        if (isset($data['photo']) && $data['photo']) {
            $data['photo'] = $data['photo']->store('negafas', 'public');
        }

        $data['service_id'] = $serviceId;

        return $this->negafaRepository->create($data);
    }

    /**
     * Met à jour une négafa.
     */
    public function updateNegafa($id, array $data)
    {
        $negafa = $this->negafaRepository->find($id);

        if (isset($data['photo']) && $data['photo']) {
            // Supprime l'ancienne photo si elle existe
            if ($negafa->photo) {
                Storage::disk('public')->delete($negafa->photo);
            }

            $data['photo'] = $data['photo']->store('negafas', 'public');
        } else {
            // Si pas de nouvelle photo, conserve l'ancienne
            unset($data['photo']);
        }

        return $this->negafaRepository->update($id, $data);
    }

    /**
     * Supprime une négafa.
     */
    public function deleteNegafa($id)
    {
        return $this->negafaRepository->delete($id);
    }

    /**
     * Ajoute un élément au portfolio.
     */
    public function addPortfolioItem($negafaId, array $data)
    {
        if (isset($data['file']) && $data['file']) {
            $data['file_path'] = $data['file']->store('negafa-portfolio', 'public');
            unset($data['file']);
        }

        return $this->negafaRepository->addPortfolioItem($negafaId, $data);
    }

    /**
     * Supprime un élément du portfolio.
     */
    public function deletePortfolioItem($itemId)
    {
        return $this->negafaRepository->deletePortfolioItem($itemId);
    }
}
