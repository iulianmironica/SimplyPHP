<?php

namespace Application\Settings;

/**
 * Description of FrameworkSettings
 *
 * @author Iulian Mironica
 */
class Config
{

    // Router settings
    const DEFAULT_CONTROLLER = 'main';
    const DEFAULT_ACTION = 'index';
    const ROUTER_SCHEME = 'http://';
    // Database settings
    const DATABASE_HOST = 'localhost';
    const DATABASE_USER = 'root';
    const DATABASE_PASSWORD = '';
    const DATABASE_NAME = '';

    // Logger settings
    public static $logger = [
        'enable' => false, // boolean
        'level' => 'debug', // emergency, alert, critical, error, warning, notice, info, debug
        'file' => 'logger.txt', // TODO: default file name
        'timestamp' => 'm-d-Y G:i:s' // leave blank for none
    ];

    // If Twig is enabled this is ignored
    const VIEW_LAYOUT_FILE = 'Layout';

    // Templating settings with Twig
    public static $twig = [
        'enable' => true,
        'cache' => 'Twig', // Cache directory
        'template' => 'Application/View', // Views/templates directory
    ];

    // SimplyPHP Framework version
    const version = '0.3'; // 0.1 Framework, 0.2 Logger, 0.3 Twig

}
