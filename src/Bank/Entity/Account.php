<?php


namespace App\Bank\Entity;


use App\Bank\Money\Money;

class Account
{
    /** @var string */
    private $number;
    /** @var Money */
    private $amount;
    /** @var User|null */
    private $user;

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @param string $number
     */
    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    /**
     * @return Money
     */
    public function getAmount(): Money
    {
        return $this->amount;
    }

    /**
     * @param Money $amount
     */
    public function setAmount(Money $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @throws \Exception
     */
    public function setUser(?User $user): void
    {
        if ($this->user !== null) {
            throw new \Exception("This account is already owned by a user");
        }

        $this->user = $user;
    }

}