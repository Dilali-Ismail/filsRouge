<?php

namespace App\Repositories\Interfaces;

interface MakeupRepositoryInterface
{
    public function getAllByService($serviceId);

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function getPortfolioItems($makeupId);

    public function addPortfolioItem($makeupId, array $data);

    public function deletePortfolioItem($itemId);
}
