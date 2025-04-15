<?php

namespace App\Services;

use App\Repositories\Interfaces\ServiceRepositoryInterface;
use App\Repositories\Interfaces\AnimationRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AnimationService
{
    protected $serviceRepository;
    protected $animationRepository;

    public function __construct(
        ServiceRepositoryInterface $serviceRepository,
        AnimationRepositoryInterface $animationRepository
    ) {
        $this->serviceRepository = $serviceRepository;
        $this->animationRepository = $animationRepository;
    }

    /**
     * Récupère tous les services Animation d'un traiteur
     */
    public function getAllAnimationServicesByTraiteur($traiteurId)
    {
        // Récupère l'ID de la catégorie 'animation'
        $animationCategoryId = DB::table('service_categories')
            ->where('name', 'animation')
            ->first()->id;

        // Récupère tous les services de type 'animation' pour ce traiteur
        return $this->serviceRepository->getByTraiteurAndCategory($traiteurId, $animationCategoryId);
    }

    /**
     * Récupère une animation spécifique
     */
    public function getAnimation($id)
    {
        return $this->animationRepository->find($id);
    }

    /**
     * Récupère ou crée un service animation pour un traiteur
     */
    public function getOrCreateAnimationService($traiteurId)
    {
        // Récupère l'ID de la catégorie 'animation'
        $animationCategoryId = DB::table('service_categories')
            ->where('name', 'animation')
            ->first()->id;

        // Vérifie si le traiteur a déjà un service animation
        $services = $this->serviceRepository->getByTraiteurAndCategory($traiteurId, $animationCategoryId);

        if ($services->count() > 0) {
            // Retourne le premier service existant
            return $services->first();
        }

        // Crée un nouveau service animation pour ce traiteur
        return $this->serviceRepository->create([
            'traiteur_id' => $traiteurId,
            'category_id' => $animationCategoryId,
            'title' => 'Animation Musicale',
            'description' => 'Service d\'animation musicale pour les mariages',
        ]);
    }

    /**
     * Crée une nouvelle animation
     */
    public function createAnimation($serviceId, array $data)
    {
        // Gestion de l'upload de photo si présent
        if (isset($data['photo']) && $data['photo']) {
            $data['photo'] = $data['photo']->store('animations', 'public');
        }

        // Crée l'animation
        return $this->animationRepository->create([
            'service_id' => $serviceId,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'photo' => $data['photo'] ?? null,
            'type' => $data['type'],
        ]);
    }

    /**
     * Met à jour une animation existante
     */
    public function updateAnimation($id, array $data)
    {
        $animation = $this->animationRepository->find($id);

        // Gestion de l'upload de photo si présent
        if (isset($data['photo']) && $data['photo']) {
            // Supprime l'ancienne photo si elle existe
            if ($animation->photo) {
                Storage::disk('public')->delete($animation->photo);
            }
            $data['photo'] = $data['photo']->store('animations', 'public');
        } else {
            // Si pas de nouvelle photo, on ne modifie pas ce champ
            unset($data['photo']);
        }

        // Met à jour l'animation
        return $this->animationRepository->update($id, [
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'photo' => $data['photo'] ?? $animation->photo,
            'type' => $data['type'],
        ]);
    }

    /**
     * Supprime une animation
     */
    public function deleteAnimation($id)
    {
        return $this->animationRepository->delete($id);
    }
}
