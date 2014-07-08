<?php

/**
 * Description of Database
 *
 * @author Iulian Mironica
 */
class Database {

    private static $resource;

    public static function init() {
        if (empty(self::$resource)) {
            try {
                // The connection has not been init connect
                $dsn = 'mysql:host=' . FrameworkSettings::DATABASE_HOST . ';dbname=' . FrameworkSettings::DATABASE_NAME . ';charset=utf8';
                self::$resource = new \PDO($dsn, FrameworkSettings::DATABASE_USER, FrameworkSettings::DATABASE_PASSWORD, array(
                    \PDO::ATTR_PERSISTENT => true,
                ));
                self::$resource->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
            } catch (Exception $e) {
                var_dump($e);
            }
            return self::$resource;
        } else {
            // Return the same resource
            return self::$resource;
        }
    }

    public static function destroy() {
        try {
            if (!empty(self::$resource)) {
                return self::$resource = null;
            }
            return true;
        } catch (Exception $ex) {
            var_dump($ex);
            return false;
        }
    }

}
