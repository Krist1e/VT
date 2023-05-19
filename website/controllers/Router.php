<?php

class Router
{
    private array $routes = [];
    private HomeController $homeController;

    public function __construct(HomeController $homeController)
    {
        $this->homeController = new HomeController();
    }

    public function addRoute(string $route, string $method) : void
    {
        $this->routes[$route] = $method;
    }

    public function handleRequest(string $uri) : void
    {
        foreach ($this->routes as $route => $method) {
            if ($route === $uri) {
                $this->homeController->$method();
                return;
            }
        }
    }
}