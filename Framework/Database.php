<?php

namespace Framework;

use Application\Settings\Config;

/**
 * Description of Database
 *
 * @author Iulian Mironica
 */
class Database
{

    private static $resource;

    public static function init()
    {
        if (empty(self::$resource)) {
            try {
                // The connection has not been init connect
                $dsn = 'mysql:host=' . Config::DATABASE_HOST . ';dbname=' . Config::DATABASE_NAME . ';charset=utf8';
                self::$resource = new \PDO($dsn, Config::DATABASE_USER, Config::DATABASE_PASSWORD, array(
                    \PDO::ATTR_PERSISTENT => true,
                ));
                self::$resource->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
            } catch (Exception $e) {
                $logger = new Logger();
                $logger->error($e);
                Util::showError('Database error ocurred. Please check the credentials and/or the connection.');
            }
            return self::$resource;
        } else {
            // Return the same resource
            return self::$resource;
        }
    }

    public static function destroy()
    {
        try {
            if (!empty(self::$resource)) {
                return self::$resource = null;
            }
            return true;
        } catch (Exception $ex) {
            Util::showError($ex);
            return false;
        }
    }

}
