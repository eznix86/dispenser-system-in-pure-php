<?php


namespace App\Bank\Repository;


use App\Bank\Entity\User;

class UserRepository implements Repository
{
    /**
     * @var User[]
     */
    private $userList = [];

    public function create($object): ?User
    {
        $this->userList[] = $object;

        return $object;
    }

    public function removeById($id)
    {
        foreach ($this->userList as $key => $user) {
            if ($user->getId() === (int) $id ) {
                unset($this->userList[$key]);
            }
        }
    }

    public function findById($id): ?User
    {
        foreach ($this->userList as $key => $user) {
            if ($user->getId() === (int) $id ) {
                return $this->userList[$key];
            }
        }

        return null;
    }
}