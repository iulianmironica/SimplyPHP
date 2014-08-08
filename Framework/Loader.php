<?php

namespace Framework;

use Framework\Util;

/**
 * Description of Loader
 *
 * @author Iulian Mironica
 */
class Loader
{
    /**
     * @param type $className
     * @param type $location
     * @param type $instantiate
     * @param type $params
     * @return \className|boolean

      public static function loadClass($className, $location = BASEPATH, $instantiate = false, $params = null)
      {
      try {
      $pathToClass = PATH . DS . $location . $className . '.php';

      if (!file_exists($pathToClass)) {
      Util::showNotFoundMessage("404 File {$pathToClass} was not found.");
      }

      if (!class_exists($className)) {
      require_once $pathToClass;
      }

      if ($instantiate AND is_null($params)) {
      return new $location . $className();
      }

      if ($instantiate AND ! is_null($params)) {
      return new $className($params);
      }
      return true;
      } catch (Exception $e) {
      Util::showError($e);
      return false;
      }
      }
     */

    /** Autoload modules declared in the configuration class/file.
     *
     * @param type $configurationArray
     */
    public static function autoloadConfigModules($configurationArray)
    {
        // var_dump($configurationArray);
        // exit();

        foreach ($configurationArray as $module => $classes) {
            switch (strtolower($module)) {
                case 'settings':
                    self::loadClasses($classes, APPLICATION_SETTINGS);
                    break;
                case 'modules':
                    self::loadClasses($classes, FRAMEWORKPATH);
                    break;
                case 'library':
                    self::loadClasses($classes, APPLICATION_LIBRARY);
                    break;
                case 'model':
                    self::loadClasses($classes, APPLICATION_MODEL);
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * @param type $classes
     * @param type $location
     */
    public static function loadClasses($classes, $location)
    {
        foreach ($classes as $class => $instantiate) {
            if (is_int($class)) {
                self::loadClass($instantiate, $location);
            } else {
                self::loadClass($class, $location, $instantiate ? true : false);
            }
        }
    }

    public static function autoload($className)
    {
        /*
          $className = ltrim($className, '\\');
          $fileName = '';
          $namespace = '';
          if ($lastNsPos = strrpos($className, '\\')) {
          $namespace = substr($className, 0, $lastNsPos);
          $className = substr($className, $lastNsPos + 1);
          $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
          }
          $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

          var_dump($className);
          var_dump($fileName);
          var_dump($namespace);

          require $fileName;
         */

        // project-specific namespace prefix
        $prefix = 'Foo\\Bar\\';

        // base directory for the namespace prefix
        $base_dir = __DIR__ . '/src/';

        // does the class use the namespace prefix?
        $len = strlen($prefix);
        if (strncmp($prefix, $className, $len) !== 0) {
            // no, move to the next registered autoloader
//            exit();
            return;
        }

        // get the relative class name
        $relative_class = substr($className, $len);

        // replace the namespace prefix with the base directory, replace namespace
        // separators with directory separators in the relative class name, append
        // with .php
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

        var_dump($file);
        exit();

        // if the file exists, require it
//        if (file_exists($file)) {
//            require $file;
//        }
    }

}
