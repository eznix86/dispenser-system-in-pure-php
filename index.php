<?php
// use `composer dump-autoload`
use App\Bank\Entity\Dispenser;
use App\Bank\Security\Security;
use App\Cli\Client;

require_once __DIR__."/vendor/autoload.php";

$userRepository = new App\Bank\Repository\UserRepository();
$accountRepository = new App\Bank\Repository\AccountRepository();
$sessionManager = new App\Bank\Session\UserSessionManager();
$security = new Security($userRepository, $sessionManager);
$dispenser = new Dispenser($accountRepository, $userRepository, $security, $sessionManager);

$app = new Client($security, $sessionManager, $dispenser);

$app->run();

