<?php

namespace Framework;

use Application\Settings\Config;

require PATH . FRAMEWORK_PATH . 'Autoloader.php';

/* TODO: create a method and put this.
 * SimplyPHP Framework version
 * ---------------------------
 * 0.1 Framework, 0.2 Logger, 0.3 Twig
 * 0.4 Linux path fixes
 * 0.5 Route improvements
 *
 * define('VERSION', '0.5');
 */

// Initialize the autoloader and set the namespaces of the project
$autoLoader = new Autoloader();
$autoLoader->setBasePath(PATH);
$autoLoader->addNamespace(str_replace(DS, '\\', FRAMEWORK_PATH), FRAMEWORK_PATH);
$autoLoader->addNamespace(str_replace(DS, '\\', APPLICATION_CONTROLLER), APPLICATION_CONTROLLER);
$autoLoader->addNamespace(str_replace(DS, '\\', APPLICATION_MODEL), APPLICATION_MODEL);
$autoLoader->addNamespace(str_replace(DS, '\\', APPLICATION_SETTINGS), APPLICATION_SETTINGS);
$autoLoader->addNamespace(str_replace(DS, '\\', APPLICATION_LIBRARY), APPLICATION_LIBRARY);
$autoLoader->register();

if (isset(Config::$doctrine['enable']) && Config::$doctrine['enable'] === true) {
    $autoLoader->addNamespace('Doctrine\Common', Config::$doctrine['pathToCommon']);
    $autoLoader->register();
}

// Set user defined namespaces
if (isset(Config::$namespaces) && !empty(Config::$namespaces)) {
    $autoLoader->addNamespaces(Config::$namespaces);
}

// Load and start the Session
$session = new \Framework\Session();

// TODO: Create a Dispatcher / preDispatch method to be called before initialization of the controller
// --------------------------------------------------------------------------------------
// Load the logger with the specified level or the fallback settings
$session->logger = new \Application\Library\KLogger\Logger(
    Config::$logger +
    [
        'level' => \Application\Library\KLogger\Logger::ALERT,
        'directory' => APPLICATION_LOG,
    ]);

// Load the Input
$input = new \Framework\Input();

// Load and the Router and prepair the URI
$router = new \Framework\Router();

// Set the controller, action and the query
$router->setUriParts();

// Set the not found fallback
$notFoundFallback = function ($router, $session, $input) {
    // Todo: check if notFound route is set
    $notFoundClassName = Config::$routes['notFound']['controller'];
    $notFoundMethodName = Config::$routes['notFound']['action'];
    $notFoundController = new $notFoundClassName();
    $notFoundController->initialize($router, $session, $input, $notFoundController);
    $notFoundController->{$notFoundMethodName}();
};

/* TODO:
 * Refactor below
 */

$pathToController = Utility::getPhpFilePath($router->controller);
if (file_exists($pathToController)) {

    $controllerName = str_replace(DS, '\\', APPLICATION_CONTROLLER) . $router->controller;

    // Load specific controller and sent the router info
    $controller = new $controllerName();

    // This will set the base controller data
    $controller->initialize($router, $session, $input, $controller);

    if (method_exists($controller, $router->action)) {
        $controller->{$router->action}();
    } else if (!is_dir($pathToController)) {

        $notFoundFallback($router, $session, $input);
        // Utility::showNotFoundMessage(" {$router->action} action not found");
    }
} else if (!is_dir($pathToController)) {

    $notFoundFallback($router, $session, $input);
    // Utility::showNotFoundMessage(" {$pathToController} controller not found");


}