<?php

namespace Application\Library;

use Application\Settings\Config;

// use Framework\Exception;

/**
 * Description of Doctrine
 *
 * @author Iulian Mironica
 */
class Doctrine
{

    public static function connect()
    {
        try {
            // Make use of Doctrine's ClassLoader
            $classLoader = new \Doctrine\Common\ClassLoader('Doctrine', PATH . Config::$doctrine['pathToLib']);
            $classLoader->register();

            $config = new \Doctrine\DBAL\Configuration();
            $connectionParams = array(
                'dbname' => Config::DATABASE_NAME,
                'user' => Config::DATABASE_USER,
                'password' => Config::DATABASE_PASSWORD,
                'host' => Config::DATABASE_HOST,
                'driver' => Config::DATABASE_DRIVER,
                'charset' => Config::DATABASE_CHARSET,
            );
            $doctrine = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
            // $doctrine->setFetchMode(\PDO::FETCH_OBJ);
            return $doctrine;
        } catch (\Exception $ex) {
            \Framework\Utility::showError('Doctrine error.', $ex);
            return false;
        }
    }

}
