<?php

namespace App\Repositories\Eloquent;

use App\Models\Animation;
use App\Repositories\Interfaces\AnimationRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class AnimationRepository implements AnimationRepositoryInterface
{
    protected $model;

    public function __construct(Animation $model)
    {
        $this->model = $model;
    }

    public function getAllByService($serviceId)
    {
        return $this->model->where('service_id', $serviceId)->get();
    }

    public function getByServiceAndType($serviceId, $type)
    {
        return $this->model->where('service_id', $serviceId)
                          ->where('type', $type)
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
        $animation = $this->find($id);
        $animation->update($data);
        return $animation;
    }

    public function delete($id)
    {
        $animation = $this->find($id);

        // Supprime la photo si elle existe
        if ($animation->photo) {
            Storage::disk('public')->delete($animation->photo);
        }

        return $animation->delete();
    }
}
