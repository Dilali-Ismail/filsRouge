<?php

namespace App\Repositories\Eloquent;

use App\Models\Makeup;
use App\Models\MakeupPortfolioItem;
use App\Repositories\Interfaces\MakeupRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class MakeupRepository implements MakeupRepositoryInterface
{
    protected $model;
    protected $portfolioModel;

    public function __construct(Makeup $model, MakeupPortfolioItem $portfolioModel)
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
        $makeup = $this->find($id);
        $makeup->update($data);
        return $makeup;
    }

    public function delete($id)
    {
        $makeup = $this->find($id);


        if ($makeup->photo) {
            Storage::disk('public')->delete($makeup->photo);
        }


        foreach ($makeup->portfolioItems as $item) {
            Storage::disk('public')->delete($item->file_path);
            $item->delete();
        }

        return $makeup->delete();
    }

    public function getPortfolioItems($makeupId)
    {
        return $this->portfolioModel->where('makeup_id', $makeupId)->get();
    }

    public function addPortfolioItem($makeupId, array $data)
    {
        $data['makeup_id'] = $makeupId;
        return $this->portfolioModel->create($data);
    }

    public function deletePortfolioItem($itemId)
    {
        $item = $this->portfolioModel->findOrFail($itemId);

       
        Storage::disk('public')->delete($item->file_path);

        return $item->delete();
    }
}
