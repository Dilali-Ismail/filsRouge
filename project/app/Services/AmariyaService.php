<?php

namespace App\Services;

use App\Repositories\Interfaces\ServiceRepositoryInterface;
use App\Repositories\Interfaces\AmariyaRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AmariyaService
{
    protected $serviceRepository;
    protected $amariyaRepository;

    public function __construct(
        ServiceRepositoryInterface $serviceRepository,
        AmariyaRepositoryInterface $amariyaRepository
    ) {
        $this->serviceRepository = $serviceRepository;
        $this->amariyaRepository = $amariyaRepository;
    }

    /**
     * Récupère tous les services Amariya d'un traiteur
     */
    public function getAllAmariyaServicesByTraiteur($traiteurId)
    {
        // Récupère l'ID de la catégorie 'amariya'
        $amariyaCategoryId = DB::table('service_categories')
            ->where('name', 'amariya')
            ->first()->id;

        // Récupère tous les services de type 'amariya' pour ce traiteur
        return $this->serviceRepository->getByTraiteurAndCategory($traiteurId, $amariyaCategoryId);
    }

    /**
     * Récupère une amariya spécifique
     */
    public function getAmariya($id)
    {
        return $this->amariyaRepository->find($id);
    }

    /**
     * Récupère ou crée un service amariya pour un traiteur
     */
    public function getOrCreateAmariyaService($traiteurId)
    {
        // Récupère l'ID de la catégorie 'amariya'
        $amariyaCategoryId = DB::table('service_categories')
            ->where('name', 'amariya')
            ->first()->id;

        // Vérifie si le traiteur a déjà un service amariya
        $services = $this->serviceRepository->getByTraiteurAndCategory($traiteurId, $amariyaCategoryId);

        if ($services->count() > 0) {
            // Retourne le premier service existant
            return $services->first();
        }

        // Crée un nouveau service amariya pour ce traiteur
        return $this->serviceRepository->create([
            'traiteur_id' => $traiteurId,
            'category_id' => $amariyaCategoryId,
            'title' => 'Amariya',
            'description' => 'Service d\'amariya pour les mariées',
        ]);
    }

    /**
     * Crée une nouvelle amariya
     */
    public function createAmariya($serviceId, array $data)
    {
        // Gestion de l'upload de photo si présent
        if (isset($data['photo']) && $data['photo']) {
            $data['photo'] = $data['photo']->store('amariyas', 'public');
        }

        // Crée l'amariya
        return $this->amariyaRepository->create([
            'service_id' => $serviceId,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'photo' => $data['photo'] ?? null,
        ]);
    }

    /**
     * Met à jour une amariya existante
     */
    public function updateAmariya($id, array $data)
    {
        $amariya = $this->amariyaRepository->find($id);

        // Gestion de l'upload de photo si présent
        if (isset($data['photo']) && $data['photo']) {
            // Supprime l'ancienne photo si elle existe
            if ($amariya->photo) {
                Storage::disk('public')->delete($amariya->photo);
            }
            $data['photo'] = $data['photo']->store('amariyas', 'public');
        } else {
            // Si pas de nouvelle photo, on ne modifie pas ce champ
            unset($data['photo']);
        }

        // Met à jour l'amariya
        return $this->amariyaRepository->update($id, [
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'photo' => $data['photo'] ?? $amariya->photo,
        ]);
    }

    /**
     * Supprime une amariya
     */
    public function deleteAmariya($id)
    {
        return $this->amariyaRepository->delete($id);
    }
}
