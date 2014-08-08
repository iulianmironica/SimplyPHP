<?php

/**
 * ------------------------------------------
 *  TODO: Finish the autoload method !!!!!!!!
 * -------------------------------------------
 */

namespace Framework;

use Framework\Loader;
use Framework\Utility;

require_once PATH . DS . FRAMEWORKPATH . 'Loader' . '.php';
// Load the Utility class
require_once PATH . DS . FRAMEWORKPATH . 'Utility' . '.php';

//spl_autoload_register('Framework\Loader::autoload');
// Load the framework config file
//Loader::loadClass('Config', APPLICATION_SETTINGS);

require_once PATH . DS . APPLICATION_SETTINGS . 'Config' . '.php';
// new \Application\Settings\Config();
// Load the logger if specified
if (isset(\Application\Settings\Config::$logger['start']) && \Application\Settings\Config::$logger['start'] === true) {
    // Loader::loadClass('Logger', FRAMEWORKPATH);
    require_once PATH . DS . FRAMEWORKPATH . 'Logger' . '.php';
    new \Framework\Logger();
}

// Load the framework util functions class
// Loader::loadClass('Util', FRAMEWORKPATH, true);
// Load and start the Session
// $session = Loader::loadClass('Session', FRAMEWORKPATH, true);
require_once PATH . DS . FRAMEWORKPATH . 'Session' . '.php';

$session = new \Framework\Session();

// Load the model
// Loader::loadClass('Model', FRAMEWORKPATH);
require_once PATH . DS . FRAMEWORKPATH . 'Model' . '.php';

// Load the View
// Loader::loadClass('View', FRAMEWORKPATH);
require_once PATH . DS . FRAMEWORKPATH . 'View' . '.php';

// Load the Base Controller
// Loader::loadClass('Controller', FRAMEWORKPATH);
require_once PATH . DS . FRAMEWORKPATH . 'Controller' . '.php';

// load the desired classes based on the config file/class
// Loader::autoloadConfigModules(\Application\Settings\Config::$autoload);
// Load and the Router and prepair the URI
// $router = Loader::loadClass('Router', FRAMEWORKPATH, true);
require_once PATH . DS . FRAMEWORKPATH . 'Router' . '.php';
$router = new \Framework\Router();

// Set the controller, action and the query
$router->setUriParts();

$pathToController = Utility::getPhpFilePath($router->controller);

if (file_exists($pathToController)) {

    require_once PATH . DS . APPLICATION_CONTROLLER . $router->controller . '.php';

    // $controllerName = str_replace("/", "\\", "/Application/Controller/" . $router->controller);
    $controllerName = APPLICATION_CONTROLLER . $router->controller;

    // Load specific controller and sent the router info
    // $controller = Loader::loadClass($router->controller, APPLICATION_CONTROLLER, true, array(
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