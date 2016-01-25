<?php

namespace Framework;

use Application\Settings\Config;

require PATH . FRAMEWORK_PATH . 'Autoloader.php';

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
if (!empty(Config::$namespaces)) {
    $autoLoader->addNamespaces(Config::$namespaces);
}

// Load and start the Session
$session = new \Framework\Session();

// TODO:
/*public static $hooks = [
    'enable' => true,
    // 'before' => '\Application\Controller\GoController',
    'after' => ['Application\Utility\Session', 'test'],
];*/
// TODO: Create a Dispatcher / preDispatch method to be called before initialization of the controller

// TODO: allow disable of KLogger
// Load the logger with the specified level or the fallback settings
$session->logger = new \IulianMironica\KLogger\Logger(
    Config::$logger +
    [
        'level' => \IulianMironica\KLogger\Logger::ALERT,
        'directory' => APPLICATION_LOG,
    ]);

// Load the Input
$input = new \Framework\Input();

// Load and the Router and prepare the URI
$router = new \Framework\Router();

// Set the controller, action and the query
$router->setUriParts();

// Set the not found fallback
$notFoundFallback = function ($router, $session, $input) {

    $notFoundClassName = Config::$routes['404']['controller'];
    $notFoundMethodName = Config::$routes['404']['action'];

    if (empty($notFoundClassName) OR empty($notFoundMethodName)) {
        Utility::showError('"Not found" controller and action not set.');
    }
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
    }
} else if (!is_dir($pathToController)) {

    $notFoundFallback($router, $session, $input);
}
