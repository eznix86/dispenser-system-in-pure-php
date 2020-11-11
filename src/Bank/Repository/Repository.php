<?php


namespace App\Bank\Repository;


interface Repository
{
    /**
     * @param $object
     */
    public function create($object);
    public function removeById($id);

    /**
     * @param $id
     */
    public function findById($id);
}