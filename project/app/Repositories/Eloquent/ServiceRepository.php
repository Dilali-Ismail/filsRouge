<?php

namespace App\Repositories\Eloquent;

use App\Models\Service;
use App\Repositories\Interfaces\ServiceRepositoryInterface;

class ServiceRepository implements ServiceRepositoryInterface
{
    protected $model;

    public function __construct(Service $model)
    {
        $this->model = $model;
    }

    public function getAllByTraiteur($traiteurId)
    {
        return $this->model->where('traiteur_id', $traiteurId)->get();
    }

    public function getByTraiteurAndCategory($traiteurId, $categoryId)
    {
        return $this->model->where('traiteur_id', $traiteurId)
                           ->where('category_id', $categoryId)
                           ->get();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $service = $this->find($id);
        $service->update($data);
        return $service;
    }

    public function delete($id)
    {
        return $this->find($id)->delete();
    }
}
