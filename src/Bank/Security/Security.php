<?php


namespace App\Bank\Security;


use App\Bank\Repository\UserRepository;
use App\Bank\Session\UserSessionManager;
use App\Container\ContainerService;

class Security
{

    /** @var bool */
    private $isGranted = false;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var UserSessionManager
     */
    private $userSessionManager;

    public function __construct(ContainerService $containerService)
    {

        $this->userRepository = $containerService->get('user_repository');
        $this->userSessionManager = $containerService->get('session');
    }

    /**
     * @param $id
     * @param $password
     * @throws \Exception
     */
    function authenticateUser($id, $password)
    {
        $user = $this->userRepository->findById($id);

        if ($user === null) {
            throw new \Exception("Access denied, id & password doesn't match");
        }

        $this->userSessionManager->createSession($user);
        return $user->getPassword() === $password;
    }

    /**
     * @return bool
     */
    public function isGranted()
    {
        return $this->isGranted;
    }

    /**
     * @param bool $isGranted
     */
    public function setIsGranted($isGranted)
    {
        $this->isGranted = $isGranted;
    }

}