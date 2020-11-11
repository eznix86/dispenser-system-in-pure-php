<?php
namespace App\Bank\Session;

use App\Bank\Entity\User;

class UserSessionManager
{
    /**
     * @var User|null
     */
    private $user;

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function createSession(?User $user): void
    {
        $this->user = $user;
    }


    public function deleteSession(): void
    {
        $this->user = null;
    }

    /**
     * @return bool
     */
    public function isConnected(): bool
    {
        return $this->user !== null;
    }
}