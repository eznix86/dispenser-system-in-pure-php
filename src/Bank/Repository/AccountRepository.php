<?php


namespace App\Bank\Repository;


use App\Bank\Entity\Account;

class AccountRepository implements Repository
{
    /**
     * @var Account[]
     */
    private $accountList = [];

    public function create($object): ?Account
    {
        $this->accountList[] = $object;

        return $object;
    }

    public function removeById($id)
    {
        foreach ($this->accountList as $key => $account) {
            if ($account->getNumber() === $id ) {
                unset($this->accountList[$key]);
            }
        }
    }

    public function findById($id): ?Account
    {
        foreach ($this->accountList as $key => $account) {
            if ($account->getNumber() === $id ) {
                return $this->accountList[$key];
            }
        }

        return null;
    }

    public function findByUserId($id): ?Account
    {
        foreach ($this->accountList as $key => $account) {
            if ($account->getUser()->getId() === $id ) {
                return $this->accountList[$key];
            }
        }

        return null;
    }
}