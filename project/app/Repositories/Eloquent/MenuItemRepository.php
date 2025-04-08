<?php

namespace App\Repositories\Eloquent;

use App\Models\MenuItem;
use App\Repositories\Interfaces\MenuItemRepositoryInterface;

class MenuItemRepository implements MenuItemRepositoryInterface
{
    protected $model;

    public function __construct(MenuItem $model)
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
        $menuItem = $this->find($id);
        $menuItem->update($data);
        return $menuItem;
    }

    public function delete($id)
    {
        return $this->find($id)->delete();
    }
}
