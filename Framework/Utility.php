<?php

namespace Framework;

/**
 * Description of Util
 *
 * @author Iulian Mironica
 */
class Utility
{

    /** Get the absolute file path of a file.
     * @param string $file
     * @param string $location
     * @param string $extension
     * @return string
     */
    public static function getPhpFilePath($file, $location = APPLICATION_CONTROLLER, $extension = '.php')
    {
        // return PATH . DS . $location . ucfirst(strtolower(trim($file))) . $extension;
        // $moduleName = array_pop(@explode("\\", $location, -1));
        return PATH . $location . self::prepairFileName($file, '') . $extension;
    }

    /**
     * @param string $url
     * @return string
     */
    public static function baseUrl($url = null)
    {
        $uri = \Application\Settings\Config::ROUTER_SCHEME . BASE_URL;
        if (!empty($url)) {
            return $uri .= $url;
        } else {
            return $uri;
        }
    }

    /**
     * @param type $fileName
     * @param type $concatenation
     * @return type
     */
    public static function prepairFileName($fileName, $concatenation = false)
    {
        if (!empty($concatenation)) {
            return ucfirst(strtolower(trim($fileName))) . $concatenation;
        } else {
            return ucfirst(strtolower(trim($fileName)));
        }
    }

    public static function showError($message)
    {
        die($message);
    }

    public static function showNotFoundMessage($message)
    {
        die($message);
    }

}
