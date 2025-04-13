<?php

namespace App\Repositories\Interfaces;

interface PhotographerRepositoryInterface
{
    public function getAllByService($serviceId);

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function getPortfolioItems($photographerId);

    public function addPortfolioItem($photographerId, array $data);

    public function deletePortfolioItem($itemId);
}
