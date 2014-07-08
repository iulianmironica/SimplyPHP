<?php

/**
 * Description of Util
 *
 * @author Iulian Mironica
 */
class Util {

    /**
     * @param type $className
     * @param type $location
     * @param type $instantiate
     * @param type $params
     * @return \className|boolean
     */
    public static function loadClass($className, $location = BASEPATH, $instantiate = false, $params = null) {
        $pathToClass = PATH . DS . $location . $className . '.php';

        if (!file_exists($pathToClass)) {
            die("404 File {$pathToClass} was not found.");
        }
        if (!class_exists($className)) {
            require_once $pathToClass;
        }
        if ($instantiate AND is_null($params)) {
            return new $className();
        }

        if ($instantiate AND ! is_null($params)) {
            return new $className($params);
        }
        return true;
    }

    /** Get the absolute file path of a file.
     *
     * @param string $file
     * @param string $location
     * @param string $extension
     * @return string
     */
    public static function getPhpFilePath($file, $location = APPLICATION_CONTROLLER, $extension = '.php') {
        // return PATH . DS . $location . ucfirst(strtolower(trim($file))) . $extension;
        // $moduleName = array_pop(@explode("\\", $location, -1));
        $moduleName = '';
        return PATH . DS . $location . self::prepairFileName($file, $moduleName) . $extension;
    }

    /**
     * @param string $url
     * @return string
     */
    public static function baseUrl($url = null) {
        // return empty($url) ? BASE_URL : BASE_URL . $url;
        return FrameworkSettings::ROUTER_SCHEME . BASE_URL . ($url ? : $url);
    }

    /**
     * @param type $fileName
     * @param type $concatenation
     * @return type
     */
    public static function prepairFileName($fileName, $concatenation = false) {
        return ucfirst(strtolower(trim($fileName))) . $concatenation ? : $concatenation;
    }

}
