<?php

namespace App\Repositories\Eloquent;

use App\Models\Clothing;
use App\Repositories\Interfaces\ClothingRepositoryInterface;

class ClothingRepository implements ClothingRepositoryInterface
{
    protected $model;

    public function __construct(Clothing $model)
    {
        $this->model = $model;
    }

    public function getAllByService($serviceId)
    {
        return $this->model->where('service_id', $serviceId)->get();
    }

    public function getByServiceAndCategory($serviceId, $category)
    {
        return $this->model->where('service_id', $serviceId)
                          ->where('category', $category)
                          ->get();
    }

    public function getByServiceAndStyle($serviceId, $style)
    {
        return $this->model->where('service_id', $serviceId)
                          ->where('style', $style)
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
        $clothingItem = $this->find($id);
        $clothingItem->update($data);
        return $clothingItem;
    }

    public function delete($id)
    {
        return $this->find($id)->delete();
    }
}
