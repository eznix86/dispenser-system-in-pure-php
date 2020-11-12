<?php
namespace App\Exception;

use Exception;

class ServiceNotFoundException extends Exception implements \App\Container\NotFoundExceptionInterface
{
}