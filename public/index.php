<?php
define('DS', DIRECTORY_SEPARATOR);

// Use DS for subdirectories
$frameworkPath = 'Framework';
$applicationPath = 'Application';
$vendorPath = 'vendor';

define('ENVIRONMENT', 'development');

define('PATH', realpath(dirname(dirname(__FILE__))) . DS);
define('FRAMEWORK_PATH', $frameworkPath . DS);
define('VENDOR_PATH', $vendorPath . DS);

define('APPLICATION_CONTROLLER', $applicationPath . DS . 'Controller' . DS);
define('APPLICATION_MODEL', $applicationPath . DS . 'Model' . DS);
define('APPLICATION_VIEW', $applicationPath . DS . 'View' . DS);
define('APPLICATION_SETTINGS', $applicationPath . DS . 'Settings' . DS);
define('APPLICATION_LIBRARY', $applicationPath . DS . 'Library' . DS);
define('APPLICATION_LOG', PATH . $applicationPath . DS . 'Log' . DS);
define('APPLICATION_CACHE', PATH . $applicationPath . DS . 'Cache' . DS);

define('BASE_URL', filter_input(INPUT_SERVER, 'HTTP_HOST'));

if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}

require_once '../Framework/Framework.php';
