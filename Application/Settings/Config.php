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
    const DATABASE_NAME = 'disertation';
    // View settings
    const VIEW_LAYOUT_FILE = 'Layout';

    // Logger settings
    public static $logger = [
        'start' => true, // boolean
        'level' => 'error', // info, debug, error
        // 'path' => PATH . DS . APPLICATION_LOG, ---- TODO
        'file' => 'logger.txt', // default file name
        'timestamp' => 'm-d-Y G:i:s' // leave blank for none
    ];

    // SimplyPHP Framework version
    const version = '0.1';

}
