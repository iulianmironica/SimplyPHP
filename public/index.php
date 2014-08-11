<?php

$frameworkPath = 'Framework';
$applicationPath = 'Application';

define('DS', DIRECTORY_SEPARATOR);
define('ENVIRONMENT', 'development');

define('PATH', realpath(dirname(dirname(__FILE__))) . DS);
define('FRAMEWORK_PATH', str_replace("\\", DS, $frameworkPath) . DS);
define('APPLICATION_PATH', str_replace("\\", DS, $applicationPath) . DS);
define('APPLICATION_SETTINGS', str_replace("\\", DS, "{$applicationPath}\Settings") . DS);

define('APPLICATION_CONTROLLER', str_replace("\\", DS, "{$applicationPath}\Controller") . DS);
define('APPLICATION_MODEL', str_replace("\\", DS, "{$applicationPath}\Model") . DS);
define('APPLICATION_VIEW', str_replace("\\", DS, "{$applicationPath}\View") . DS);
define('APPLICATION_LIBRARY', str_replace("\\", DS, "{$applicationPath}\Library") . DS);
define('APPLICATION_LOG', str_replace("\\", DS, "{$applicationPath}\Log") . DS);

define('BASE_URL', filter_input(INPUT_SERVER, 'HTTP_HOST'));

if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}

require_once '../Framework/Framework.php';
