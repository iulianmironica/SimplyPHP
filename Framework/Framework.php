<?php

namespace Framework;

require PATH . FRAMEWORK_PATH . 'Autoloader.php';

/*
 * SimplyPHP Framework version
 * ---------------------------
 * 0.1 Framework, 0.2 Logger, 0.3 Twig
 */
define('VERSION', '0.3');

// Initialize the autoloader and set the namespaces of the project
$autoloader = new Autoloader();
$autoloader->setBasePath(PATH);
$autoloader->addNamespace('Framework', '/Framework');
$autoloader->addNamespace('Application\Controller', '/Application/Controller');
$autoloader->addNamespace('Application\Model', '/Application/Model');
$autoloader->addNamespace('Application\Settings', 'Application/Settings');
$autoloader->addNamespace('Application\Library', 'Application/Library');
$autoloader->addNamespace('vendor', 'vendor');
$autoloader->register();

// Set user defined namespaces
if (isset(\Application\Settings\Config::$namespaces) && !empty(\Application\Settings\Config::$namespaces)) {
    $autoloader->addNamespaces(\Application\Settings\Config::$namespaces);
}

// Load and start the Session
$session = new \Framework\Session();

// TODO: Create a Dispatcher / preDispatch method to be called before initialization of the controller
// --------------------------------------------------------------------------------------
// Load the logger with the specified level or the default level
if (isset(\Application\Settings\Config::$logger['level'])) {
    $session->logger = new \Application\Library\KLogger\Logger(APPLICATION_LOG, \Application\Settings\Config::$logger['level'], \Application\Settings\Config::$logger);
} else {
    $session->logger = new \Application\Library\KLogger\Logger(APPLICATION_LOG, \Application\Library\KLogger\Logger::ALERT, \Application\Settings\Config::$logger);
}

// Load and the Router and prepair the URI
$router = new \Framework\Router();

// Set the controller, action and the query
$router->setUriParts();

/* TODO:
 * Refactor below
 */

$pathToController = Utility::getPhpFilePath($router->controller);
if (file_exists($pathToController)) {

    $controllerName = APPLICATION_CONTROLLER . $router->controller;

    // Load specific controller and sent the router info
    $controller = new $controllerName();

    // This will set the base controller data
    $controller->initialize($router, $session, $controller);

    if (method_exists($controller, $router->action)) {
        $controller->{$router->action}($router);
    } else if (!is_dir($pathToController)) {
        Utility::showNotFoundMessage(" {$router->action} action not found");
    }
} else if (!is_dir($pathToController)) {
    Utility::showNotFoundMessage(" {$pathToController} controller not found");
}