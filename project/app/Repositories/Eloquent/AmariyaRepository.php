<?php

namespace App\Repositories\Eloquent;

use App\Models\Amariya;
use App\Repositories\Interfaces\AmariyaRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class AmariyaRepository implements AmariyaRepositoryInterface
{
    protected $model;

    public function __construct(Amariya $model)
    {
        $this->model = $model;
    }

    public function getAllByService($serviceId)
    {
        return $this->model->where('service_id', $serviceId)->get();
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
        $amariya = $this->find($id);
        $amariya->update($data);
        return $amariya;
    }

    public function delete($id)
    {
        $amariya = $this->find($id);

        if ($amariya->photo) {
            Storage::disk('public')->delete($amariya->photo);
        }

        return $amariya->delete();
    }
}
