<?php
namespace App\Cli;

use App\Bank\Entity\Dispenser;
use App\Bank\Security\Security;
use App\Bank\Money\Money;
use App\Bank\Session\UserSessionManager;

class Client
{
    /**
     * @var Security
     */
    private $security;
    /**
     * @var Dispenser
     */
    private $dispenser;
    /**
     * @var UserSessionManager
     */
    private $userSessionManager;

    public function __construct(Security $security, UserSessionManager $userSessionManager, Dispenser $dispenser)
    {
        $this->security = $security;
        $this->dispenser = $dispenser;
        $this->userSessionManager = $userSessionManager;
    }


    function run() {
        $this->init();
        while(true) {
            exec('clear');
            echo "\n\n======== DISPENSER SERVICE ==========\n\n";
            $id = readline("Insert Your User ID: ");
            $password = readline("Insert Your User Password: ");
            $authenticated = false;

            try {
                $authenticated = $this->security->authenticateUser($id, $password);
            } catch (\Exception $exception) {
                echo "\n\nERROR: ".$exception->getMessage()."\n\n";
            }

            if ($authenticated) {

                while ($this->userSessionManager->isConnected()) {
                    try {
                        $this->dispenser->init();

                    } catch (\Exception $exception) {
                        echo "\n\nERROR: ".$exception->getMessage()."\n\n";
                        $this->userSessionManager->deleteSession();
                    }

                    echo "\n1. View Balance\n";
                    echo "\n2. Withdraw Account\n";
                    echo "\n3. Insert Money\n";
                    echo "\n4. Log out\n";
                    echo "\n0. Delete Account & Bank Account\n";
                    $selection = readline("Make your selection:");
                    switch ((int) $selection) {
                        case 0:
                            $this->dispenser->deleteAccount();
                            exec('clear');
                            break;
                        case 1:
                            $this->dispenser->viewBalance();
                            break;
                        case 2:
                            $withDraw = readline("Amount to withdraw (can be 12 or 12.90): ");
                            $money = (new Money())->setAmountWithDecimal($withDraw);
                            try {
                                $this->dispenser->withDraw($money);
                                $this->userSessionManager->deleteSession();

                            } catch (\Exception $exception) {
                                echo "\n\nERROR: ".$exception->getMessage()."\n\n";
                            }

                            break;
                        case 3:
                            $insert = readline("Amount to insert (can be 12 or 12.90): ");
                            $money = (new Money())->setAmountWithDecimal($insert);
                            $this->dispenser->insert($money);
                            $this->userSessionManager->deleteSession();
                            break;
                        case 4:
                            $this->userSessionManager->deleteSession();
                            break;
                        default:
                            echo "\n\n Wrong input. Try again";
                    }
                }
            }
        }
    }

    public function init() {
         do {
            try {
                $this->dispenser->createAccount();
            } catch (\Exception $exception) {
                echo "\n\nERROR: ".$exception->getMessage()."\n\n";
                break;
            }

            $choice = readline("Continue create accounts? (y/n) ");

        } while (preg_match("/y/i", $choice) !== 0);
    }
}