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
    const DATABASE_HOST = 'localhost';
    const DATABASE_USER = 'root';
    const DATABASE_PASSWORD = '';
    const DATABASE_NAME = '';

    /* --------------------- Logger --------------------- */

    /** Logger settings
     * @var array
     */
    public static $logger = [
        'level' => 'debug', // emergency, alert, critical, error, warning, notice, info, debug
        'file' => 'logger.txt', // TODO: default file name
        'timestamp' => 'm-d-Y G:i:s' // leave blank for none
    ];

    /* --------------------- View --------------------- */

    // If Twig is enabled this is ignored
    const VIEW_LAYOUT_FILE = 'Layout';

    /** Templating settings with Twig
     * @var array
     */
    public static $twig = [
        'enable' => true,
        'cache' => 'Twig', // Cache directory
        'template' => 'Application/View', // Views/templates directory
    ];

    /* --------------------- Namespaces --------------------- */

    /** User defined namespace registration
     * @var array
     */
    public static $namespaces = [
            // 'namespace\namespace' => 'path/to/the/directory'
            // 'Application\Library' => 'Application/Library',
    ];

    // Application version
    const version = '1';

}
