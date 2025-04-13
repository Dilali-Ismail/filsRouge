<?php

namespace App\Repositories\Eloquent;

use App\Models\Photographer;
use App\Models\PhotographerPortfolioItem;
use App\Repositories\Interfaces\PhotographerRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class PhotographerRepository implements PhotographerRepositoryInterface
{
    protected $model;
    protected $portfolioModel;

    public function __construct(Photographer $model, PhotographerPortfolioItem $portfolioModel)
    {
        $this->model = $model;
        $this->portfolioModel = $portfolioModel;
    }

    public function getAllByService($serviceId)
    {
        return $this->model->where('service_id', $serviceId)->get();
    }

    public function find($id)
    {
        return $this->model->with('portfolioItems')->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $photographer = $this->find($id);
        $photographer->update($data);
        return $photographer;
    }

    public function delete($id)
    {
        $photographer = $this->find($id);

        // Supprime la photo si elle existe
        if ($photographer->photo) {
            Storage::disk('public')->delete($photographer->photo);
        }

        // Supprime tous les éléments du portfolio
        foreach ($photographer->portfolioItems as $item) {
            Storage::disk('public')->delete($item->file_path);
            $item->delete();
        }

        return $photographer->delete();
    }

    public function getPortfolioItems($photographerId)
    {
        return $this->portfolioModel->where('photographer_id', $photographerId)->get();
    }

    public function addPortfolioItem($photographerId, array $data)
    {
        $data['photographer_id'] = $photographerId;
        return $this->portfolioModel->create($data);
    }

    public function deletePortfolioItem($itemId)
    {
        $item = $this->portfolioModel->findOrFail($itemId);

        // Supprime le fichier
        Storage::disk('public')->delete($item->file_path);

        return $item->delete();
    }
}
