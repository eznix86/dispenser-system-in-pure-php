<?php
// use `composer dump-autoload`
use App\Bank\Entity\Dispenser;
use App\Bank\Security\Security;
use App\Cli\Client;

require_once __DIR__."/vendor/autoload.php";

$container = [];

$container["container"] = new App\Container\ContainerService();

$container["user_repository"] = new App\Bank\Repository\UserRepository();
$container["container"]->setContainer($container);

$container["account_repository"] = new App\Bank\Repository\AccountRepository();
$container["container"]->setContainer($container);

$container["session"] = new App\Bank\Session\UserSessionManager();
$container["container"]->setContainer($container);

$container["security"] = new Security($container["container"]);
$container["container"]->setContainer($container);

$container["dispenser"] = new Dispenser($container["account_repository"], $container["user_repository"], $container["security"], $container['session']);
$container["container"]->setContainer($container);

$app = new Client($container["security"], $container['session'], $container["dispenser"]);

$container["container"]->setContainer($container);




$app->run();

