<?php

namespace App\Repositories\Eloquent;

use App\Models\Negafa;
use App\Models\NegafaPortfolioItem;
use App\Repositories\Interfaces\NegafaRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class NegafaRepository implements NegafaRepositoryInterface
{
    protected $model;
    protected $portfolioModel;

    public function __construct(Negafa $model, NegafaPortfolioItem $portfolioModel)
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
        $negafa = $this->find($id);
        $negafa->update($data);
        return $negafa;
    }

    public function delete($id)
    {
        $negafa = $this->find($id);

        // Supprime la photo si elle existe
        if ($negafa->photo) {
            Storage::disk('public')->delete($negafa->photo);
        }

        // Supprime tous les éléments du portfolio
        foreach ($negafa->portfolioItems as $item) {
            Storage::disk('public')->delete($item->file_path);
            $item->delete();
        }

        return $negafa->delete();
    }

    public function getPortfolioItems($negafaId)
    {
        return $this->portfolioModel->where('negafa_id', $negafaId)->get();
    }

    public function addPortfolioItem($negafaId, array $data)
    {
        $data['negafa_id'] = $negafaId;
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
