<?php

namespace Framework;

require PATH . DS . FRAMEWORK_PATH . 'Autoloader.php';

// Initialize the autoloader and set the namespaces of the project
$autoloader = new Autoloader();
$autoloader->setBasePath(PATH);
$autoloader->addNamespace('Framework', '/Framework');
$autoloader->addNamespace('Application\Controller', '/Application/Controller');
$autoloader->addNamespace('Application\Model', '/Application/Model');
$autoloader->addNamespace('Application\Settings', 'Application/Settings');
$autoloader->register();

// Load the logger if specified - PUT THE LOGGER ON THE SESSION
//if (isset(\Application\Settings\Config::$logger['start']) && \Application\Settings\Config::$logger['start'] === true) {
//    new \Framework\Logger();
//}
// Load and start the Session
$session = new \Framework\Session();

// Load and the Router and prepair the URI
$router = new \Framework\Router();

// Set the controller, action and the query
$router->setUriParts();

$pathToController = Utility::getPhpFilePath($router->controller);
if (file_exists($pathToController)) {

    // require_once PATH . DS . APPLICATION_CONTROLLER . $router->controller . '.php';
    // $controllerName = str_replace("/", "\\", "/Application/Controller/" . $router->controller);
    $controllerName = APPLICATION_CONTROLLER . $router->controller;

    // Load specific controller and sent the router info
    $controller = new $controllerName(array(
        'router' => $router,
        'session' => $session
    ));

    if (method_exists($controller, $router->action)) {
        $controller->{$router->action}($router);
    } else if (!is_dir($pathToController)) {
        Utility::showNotFoundMessage("404 {$router->action} action not found");
    }
} else if (!is_dir($pathToController)) {
    Utility::showNotFoundMessage("404 {$pathToController} controller not found");
}