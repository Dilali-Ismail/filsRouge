<?php

namespace App\Repositories\Interfaces;

interface ServiceRepositoryInterface
{

    public function getAllByTraiteur($traiteurId);
    
    public function getByTraiteurAndCategory($traiteurId, $categoryId);

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);
}
