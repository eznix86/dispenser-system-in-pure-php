<?php


namespace App\Container;


use App\Exception\ServiceNotFoundException;

class ContainerService implements ContainerInterface
{
    private $container = [];

    public function get($id)
    {
        if (!$this->has($id)) {
            throw new ServiceNotFoundException("Service $id not found");
        }
        return $this->container[$id];
    }

    /**
     * @return mixed
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param mixed $container
     */
    public function setContainer($container): void
    {
        $this->container = $container;
    }

    public function has($id)
    {
        return isset($this->container[$id]);
    }
}