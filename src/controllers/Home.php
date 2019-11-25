<?php

use Psr\Container\ContainerInterface;

class HomeController
{
    protected $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function index($request, $response, $args)
    {
        return $response->withJson($this->container['user']);
    }

}
