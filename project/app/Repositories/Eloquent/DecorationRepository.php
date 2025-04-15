<?php

namespace App\Repositories\Eloquent;

use App\Models\Decoration;
use App\Repositories\Interfaces\DecorationRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class DecorationRepository implements DecorationRepositoryInterface
{
    protected $model;

    public function __construct(Decoration $model)
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
        $decoration = $this->find($id);
        $decoration->update($data);
        return $decoration;
    }

    public function delete($id)
    {
        $decoration = $this->find($id);

        // Supprime la photo si elle existe
        if ($decoration->photo) {
            Storage::disk('public')->delete($decoration->photo);
        }

        return $decoration->delete();
    }
}
