<?php
require_once('./vendor/autoload.php');
require_once('./controllers/HomeController.php');
require_once('./controllers/Router.php');

$homeController = new HomeController();

$router = new Router($homeController);

$router->addRoute('/', 'index');
$router->addRoute('/registration', 'registration');
$router->addRoute('/registration/signup', 'signup');
$router->addRoute('/login', 'login');
$router->addRoute('/login/signin', 'signin');
$router->addRoute('/shop', 'shop');
$router->addRoute('/about', 'about');
$router->addRoute('/login/logout', 'logout');

$uri = $_SERVER['REQUEST_URI'];
try {
    $router->handleRequest($uri);
} catch (Exception $e) {
    http_response_code(404);
    echo '404 Not Found';
} catch (Error $e) {
    http_response_code(500);
    echo '500 Internal Server Error';
}