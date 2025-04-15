<?php

namespace App\Services;

use App\Repositories\Interfaces\ServiceRepositoryInterface;
use App\Repositories\Interfaces\DecorationRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DecorationService
{
    protected $serviceRepository;
    protected $decorationRepository;

    public function __construct(
        ServiceRepositoryInterface $serviceRepository,
        DecorationRepositoryInterface $decorationRepository
    ) {
        $this->serviceRepository = $serviceRepository;
        $this->decorationRepository = $decorationRepository;
    }

    public function getAllDecorationServicesByTraiteur($traiteurId)
    {
        // Récupère l'ID de la catégorie 'decoration'
        $decorationCategoryId = DB::table('service_categories')
            ->where('name', 'decoration')
            ->first()->id;

        // Récupère tous les services de type 'decoration' pour ce traiteur
        return $this->serviceRepository->getByTraiteurAndCategory($traiteurId, $decorationCategoryId);
    }

    /**
     * Récupère une décoration spécifique
     */
    public function getDecoration($id)
    {
        return $this->decorationRepository->find($id);
    }

    /**
     * Récupère ou crée un service decoration pour un traiteur
     */
    public function getOrCreateDecorationService($traiteurId)
    {
        // Récupère l'ID de la catégorie 'decoration'
        $decorationCategoryId = DB::table('service_categories')
            ->where('name', 'decoration')
            ->first()->id;

        // Vérifie si le traiteur a déjà un service decoration
        $services = $this->serviceRepository->getByTraiteurAndCategory($traiteurId, $decorationCategoryId);

        if ($services->count() > 0) {
            // Retourne le premier service existant
            return $services->first();
        }

        // Crée un nouveau service decoration pour ce traiteur
        return $this->serviceRepository->create([
            'traiteur_id' => $traiteurId,
            'category_id' => $decorationCategoryId,
            'title' => 'Décoration',
            'description' => 'Service de décoration pour les mariages',
        ]);
    }

    /**
     * Crée une nouvelle décoration
     */
    public function createDecoration($serviceId, array $data)
    {
        // Gestion de l'upload de photo si présent
        if (isset($data['photo']) && $data['photo']) {
            $data['photo'] = $data['photo']->store('decorations', 'public');
        }

        // Crée la décoration
        return $this->decorationRepository->create([
            'service_id' => $serviceId,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'photo' => $data['photo'] ?? null,
        ]);
    }

    /**
     * Met à jour une décoration existante
     */
    public function updateDecoration($id, array $data)
    {
        $decoration = $this->decorationRepository->find($id);

        // Gestion de l'upload de photo si présent
        if (isset($data['photo']) && $data['photo']) {
            // Supprime l'ancienne photo si elle existe
            if ($decoration->photo) {
                Storage::disk('public')->delete($decoration->photo);
            }
            $data['photo'] = $data['photo']->store('decorations', 'public');
        } else {
            // Si pas de nouvelle photo, on ne modifie pas ce champ
            unset($data['photo']);
        }

        // Met à jour la décoration
        return $this->decorationRepository->update($id, [
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'photo' => $data['photo'] ?? $decoration->photo,
        ]);
    }

    public function deleteDecoration($id)
    {
        return $this->decorationRepository->delete($id);
    }
}
