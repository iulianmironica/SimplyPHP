<?php

require_once PATH . DS . FRAMEWORKPATH . 'Util' . '.php';

// Load the framework config file
Util::loadClass('FrameworkSettings', APPLICATION_SETTINGS);

if (isset(FrameworkSettings::$logger['start']) && FrameworkSettings::$logger['start'] === true) {
    // Load the logger if set
    Util::loadClass('Logger', FRAMEWORKPATH);
}

// Load the framework util functions class
Util::loadClass('Util', FRAMEWORKPATH, true);

// Load and start the Session
$session = Util::loadClass('Session', FRAMEWORKPATH, true);

// Load the model
Util::loadClass('Model', FRAMEWORKPATH);

// Load the View
Util::loadClass('View', FRAMEWORKPATH);

// Load the Base Controller
Util::loadClass('Controller', FRAMEWORKPATH);

// TODO: load the desired classes based on the config file/class
Util::autoloadConfigModules(FrameworkSettings::$autoload);

// Load and the Router and prepair the URI
$router = Util::loadClass('Router', FRAMEWORKPATH, true);

// Set the controller, action and the query
$router->setUriParts();

$pathToController = Util::getPhpFilePath($router->controller);

if (file_exists($pathToController)) {

    // Load specific controller and sent the router info
    $controller = Util::loadClass($router->controller, APPLICATION_CONTROLLER, true, array(
                'router' => $router,
                'session' => $session
    ));

    if (method_exists($controller, $router->action)) {
        $controller->{$router->action}($router);
    } else if (!is_dir($pathToController)) {
        Util::notFoundMessage("404 {$router->action} action not found");
    }
} else if (!is_dir($pathToController)) {
    Util::notFoundMessage("404 {$pathToController} controller not found");
}