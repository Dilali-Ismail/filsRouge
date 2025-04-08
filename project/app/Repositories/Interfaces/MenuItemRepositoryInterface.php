<?php

namespace App\Repositories\Interfaces;

interface MenuItemRepositoryInterface
{

    public function getAllByService($serviceId);

    public function getByServiceAndCategory($serviceId, $category);

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);
}
