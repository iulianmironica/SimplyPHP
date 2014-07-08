<?php

/**
 * Description of FrameworkSettings
 *
 * @author Iulian Mironica
 */
class FrameworkSettings {

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

    // TODO:
    // Classes that will be loaded automatically
    public static $autoload = array(
        // DB is loaded in the Model
        // 'Database',
        'Input',
        'Helper',
        'ApplicationSettings',
        'ProductModel',
    );

    // SimplyPHP Framework version
    const version = '1.0';

}
