<?php

namespace App\Repositories\Interfaces;

interface NegafaRepositoryInterface
{
    public function getAllByService($serviceId);

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function getPortfolioItems($negafaId);

    public function addPortfolioItem($negafaId, array $data);

    public function deletePortfolioItem($itemId);
}
