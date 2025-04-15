<?php

namespace App\Repositories\Interfaces;

interface AnimationRepositoryInterface
{
    public function getAllByService($serviceId);

    public function getByServiceAndType($serviceId, $type);

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);
}
