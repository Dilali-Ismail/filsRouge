<?php

namespace App\Services;

use App\Repositories\Interfaces\ServiceRepositoryInterface;
use App\Repositories\Interfaces\SalleRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SalleService
{
    protected $serviceRepository;
    protected $salleRepository;

    public function __construct(
        ServiceRepositoryInterface $serviceRepository,
        SalleRepositoryInterface $salleRepository
    ) {
        $this->serviceRepository = $serviceRepository;
        $this->salleRepository = $salleRepository;
    }

    /**
     * Récupère tous les services Salle d'un traiteur
     */
    public function getAllSalleServicesByTraiteur($traiteurId)
    {
        // Récupère l'ID de la catégorie 'salles'
        $sallesCategoryId = DB::table('service_categories')
            ->where('name', 'salles')
            ->first()->id;

        // Récupère tous les services de type 'salles' pour ce traiteur
        return $this->serviceRepository->getByTraiteurAndCategory($traiteurId, $sallesCategoryId);
    }

    /**
     * Récupère une salle spécifique
     */
    public function getSalle($id)
    {
        return $this->salleRepository->find($id);
    }

    /**
     * Récupère ou crée un service salles pour un traiteur
     */
    public function getOrCreateSalleService($traiteurId)
    {
        // Récupère l'ID de la catégorie 'salles'
        $sallesCategoryId = DB::table('service_categories')
            ->where('name', 'salles')
            ->first()->id;

        // Vérifie si le traiteur a déjà un service salles
        $services = $this->serviceRepository->getByTraiteurAndCategory($traiteurId, $sallesCategoryId);

        if ($services->count() > 0) {
            // Retourne le premier service existant
            return $services->first();
        }

        // Crée un nouveau service salles pour ce traiteur
        return $this->serviceRepository->create([
            'traiteur_id' => $traiteurId,
            'category_id' => $sallesCategoryId,
            'title' => 'Salles de Réception',
            'description' => 'Service de location de salles pour les mariages',
        ]);
    }

    /**
     * Crée une nouvelle salle
     */
    public function createSalle($serviceId, array $data)
    {
        // Gestion de l'upload de photo si présent
        if (isset($data['photo']) && $data['photo']) {
            $data['photo'] = $data['photo']->store('salles', 'public');
        }

        // Crée la salle
        return $this->salleRepository->create([
            'service_id' => $serviceId,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'photo' => $data['photo'] ?? null,
            'location' => $data['location'],
            'tables_count' => $data['tables_count']
        ]);
    }

    /**
     * Met à jour une salle existante
     */
    public function updateSalle($id, array $data)
    {
        $salle = $this->salleRepository->find($id);

        // Gestion de l'upload de photo si présent
        if (isset($data['photo']) && $data['photo']) {
            // Supprime l'ancienne photo si elle existe
            if ($salle->photo) {
                Storage::disk('public')->delete($salle->photo);
            }
            $data['photo'] = $data['photo']->store('salles', 'public');
        } else {
            // Si pas de nouvelle photo, on ne modifie pas ce champ
            unset($data['photo']);
        }

        // Met à jour la salle
        return $this->salleRepository->update($id, [
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'photo' => $data['photo'] ?? $salle->photo,
            'location' => $data['location'],
            'tables_count' => $data['tables_count']
        ]);
    }

    /**
     * Supprime une salle
     */
    public function deleteSalle($id)
    {
        return $this->salleRepository->delete($id);
    }
}
