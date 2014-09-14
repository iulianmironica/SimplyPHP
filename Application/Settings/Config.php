<?php

namespace Application\Settings;

/**
 * Description of FrameworkSettings
 *
 * @author Iulian Mironica
 */
class Config
{
    /* --------------------- Router --------------------- */

    const DEFAULT_CONTROLLER = 'main';
    const DEFAULT_ACTION = 'index';
    const ROUTER_SCHEME = 'http://';

    /* --------------------- Database --------------------- */
    const DATABASE_TYPE = 'mysql';
    const DATABASE_DRIVER = 'pdo_mysql';
    const DATABASE_CHARSET = 'utf8';
    const DATABASE_HOST = 'localhost';
    const DATABASE_USER = 'root';
    const DATABASE_PASSWORD = '';
    const DATABASE_NAME = '';

    /** @var array Doctrine DBAL settings: http://www.doctrine-project.org/projects/dbal.html */
    public static $doctrine = [
        'enable' => true,
        // Relative path e.g: '/vendor/doctrine/dbal/lib/'
        'pathToLib' => '/vendor/doctrine/dbal/lib/',
        // Relative path e.g: 'vendor/doctrine/common/lib/Doctrine/Common'
        'pathToCommon' => 'vendor/doctrine/common/lib/Doctrine/Common',
    ];

    /* --------------------- Logger --------------------- */

    /** @var array Logger settings */
    public static $logger = [
        'level' => 'debug', // emergency, alert, critical, error, warning, notice, info, debug
        'file' => 'logger.txt', // TODO: default file name
        'timestamp' => 'm-d-Y G:i:s' // leave blank for none
    ];

    /* --------------------- View --------------------- */

    /** @var string View layout file. If Twig is enabled this is ignored. */
    const VIEW_LAYOUT_FILE = 'Layout';

    /** @var array Templating settings with Twig */
    public static $twig = [
        'enable' => true,
        'cache' => 'Twig', // Cache directory
        'template' => 'Application/View', // Views/templates directory
    ];

    /* --------------------- Namespaces --------------------- */

    /** @var array User defined namespace registration */
    public static $namespaces = [
            // 'namespace\namespace' => 'path/to/the/directory'
            // 'Application\Library' => 'Application/Library',
    ];

    /** @var string Application version */
    const version = '1.0';

}
