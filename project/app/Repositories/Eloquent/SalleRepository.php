<?php

namespace App\Repositories\Eloquent;

use App\Models\Salle;
use App\Repositories\Interfaces\SalleRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class SalleRepository implements SalleRepositoryInterface
{
    protected $model;

    public function __construct(Salle $model)
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
        $salle = $this->find($id);
        $salle->update($data);
        return $salle;
    }

    public function delete($id)
    {
        $salle = $this->find($id);

        // Supprime la photo si elle existe
        if ($salle->photo) {
            Storage::disk('public')->delete($salle->photo);
        }

        return $salle->delete();
    }
}
