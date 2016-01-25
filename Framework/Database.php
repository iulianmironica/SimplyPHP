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
        if (self::$resource instanceof \PDO) {
            // New resource
            try {
                // The connection has not been init connect
                $dsn = Config::DATABASE_TYPE . ':host=' . Config::DATABASE_HOST . ';dbname=' . Config::DATABASE_NAME . ';charset=utf8';
                self::$resource = new \PDO($dsn, Config::DATABASE_USER, Config::DATABASE_PASSWORD, array(
                    \PDO::ATTR_PERSISTENT => true,
                ));

                self::$resource->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
                self::$resource->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                Utility::showError('Database error occurred. Please check the credentials and/or the connection.', $e);
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
        } catch (\PDOException $ex) {
            Utility::showError('Database error:', $ex);
            return false;
        }
    }

}
