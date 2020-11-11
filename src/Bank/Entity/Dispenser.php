<?php
namespace App\Bank\Entity;

use App\Bank\Repository\AccountRepository;
use App\Bank\Repository\UserRepository;
use App\Bank\Security\Security;
use App\Bank\Money\Money;
use App\Bank\Session\UserSessionManager;

class Dispenser
{
    /**
     * @var Account
     */
    private $selectedAccount;
    /**
     * @var AccountRepository
     */
    private $accountRepository;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var UserSessionManager
     */
    private $userSessionManager;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(AccountRepository $accountRepository, UserRepository $userRepository, Security $security, UserSessionManager $userSessionManager)
    {
        $this->accountRepository = $accountRepository;
        $this->security = $security;
        $this->userSessionManager = $userSessionManager;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws \Exception
     */
    public function init()
    {
        $account = $this->accountRepository->findByUserId($this->userSessionManager->getUser()->getId());

        if ($account === null)
        {
            throw new \Exception("No bank account for this user");
        }

        $this->setSelectedAccount($account);
    }

    function viewBalance()
    {
        $amount = (string) $this->selectedAccount->getAmount();
        echo sprintf("\n\n%s has %s\n\n", $this->userSessionManager->getUser(), $amount);
    }

    /**
     * @param Money $money
     * @throws \Exception
     */
    function withDraw(Money $money)
    {
        $amountChanged = $this->selectedAccount->getAmount()->substract($money);

        if ($amountChanged->getAmount() < 0) {
            throw new \Exception("Not enough balance");
        }

        $this->selectedAccount->setAmount($amountChanged);

        echo sprintf("\nYou successfully withdraw %s, current amount: %s\n", $money, $amountChanged);

    }

    /**
     * @param Money $money
     */
    function insert(Money $money)
    {
        $amountChanged = $this->selectedAccount->getAmount()->add($money);

        $this->selectedAccount->setAmount($amountChanged);

        echo sprintf("\nYou successfully add %s, current amount: %s\n", $money, $amountChanged);
    }

    /**
     * @throws \Exception
     */
    function createAccount()
    {
        echo "\n\n======== Account creation ========\n\n";
        $user = new User();
        $id = readline("Insert Your ID (number): ");
        $user->setId((int) $id);
        $password = readline("Insert Your Password: ");

        $user->setPassword($password);
        $name = readline("Insert Your Name: ");

        $user->setName($name);

        $this->userRepository->create($user);

        $account = new Account();

        $amount = readline("Insert Your Amount (can be 12 or 12.90): ");

        $money = (new Money())->setAmountWithDecimal($amount);

        $account->setAmount($money);
        $account->setUser($user);

        $dateTime = (new \DateTime())->format(DATE_ATOM);
        $account->setNumber(md5($dateTime));
        $this->accountRepository->create($account);
        echo sprintf("\n\nYou successfully created your account %s, user id: %s\n", $account->getNumber(), $user->getId());
    }

    function deleteAccount()
    {
        $choice = readline("do you really want to destroy your bank account and your user account? (y/n) ");

        switch ($choice) {
            case 'y' || 'Y':
                $this->accountRepository->removeById($this->selectedAccount->getNumber());
                $this->userRepository->removeById($this->selectedAccount->getUser()->getId());
                echo sprintf("\n\nAccount & User deleted\n\n");
                $this->userSessionManager->deleteSession();
                echo "\nLogging out...\n";
                sleep(3);
                return;
            case 'n' || 'N':
        }
    }

    /**
     * @return Account
     */
    private function getSelectedAccount(): Account
    {
        return $this->selectedAccount;
    }

    /**
     * @param Account $selectedAccount
     */
    private function setSelectedAccount(Account $selectedAccount): void
    {
        $this->selectedAccount = $selectedAccount;
    }
}