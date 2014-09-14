<?php

namespace Framework;

use Framework\Utility;
use Application\Settings\Config;

/**
 * Description of View
 *
 * @author Iulian Mironica
 */
class View
{

    public $controller;
    public $action;
    public $session;
    public $layout;
    public $variables = array();
    public $loadedViews = array();

    /**
     * @param \Framework\Router $router
     * @param \Framework\Session $session
     */
    public function __construct(Router $router, Session $session)
    {
        $this->controller = $router->controller;
        $this->action = $router->action;
        $this->session = $session;

        // Set the default layout
        $this->layout = Utility::prepairFileName(Config::VIEW_LAYOUT_FILE, 'View');
    }

    /**
     * @param type $indexOrArray
     * @param type $value
     */
    public function set($indexOrArray, $value = null)
    {
        if (is_array($indexOrArray) && is_null($value)) {
            array_merge($indexOrArray, $this->variables);
        } else {
            $this->variables[$indexOrArray] = $value;
        }
    }

    /**
     * @param type $layoutFileNameAndLocation
     */
    public function setLayoutFile($layoutFileNameAndLocation)
    {
        $this->layout = Utility::prepairFileName($layoutFileNameAndLocation, 'View');
    }

    /**
     * @param type $variables
     * @param type $layout
     * @param type $return
     * @return type
     */
    public function render($variables = null, $layout = null, $return = false)
    {

        if (empty($layout) && empty($this->layout)) {
            Utility::showNotFoundMessage('View layout is not set, use: $this->view->setLayoutFile("Layout");');
        }

        $layout = empty($layout) ? $this->layout : Utility::prepairFileName($layout, 'View');

        if (is_array($variables)) {
            // Set variables to be visible to the view also
            $this->variables = array_merge($variables, $this->variables);
        }

        $fileNameAndPath = Utility::getPhpFilePath($layout, APPLICATION_VIEW);
        if (!file_exists($fileNameAndPath)) {
            Utility::showNotFoundMessage('View file not found in ' . $fileNameAndPath);
        }

        if (!in_array($layout, $this->loadedViews)) {
            $this->loadedViews[] = $layout;

            ob_start();
            include $fileNameAndPath;
            $rendered = ob_get_contents();
            ob_end_clean();
            if ($return === false) {
                echo $rendered;
            } else {
                return $rendered;
            }
        }
    }

    // Make $this->baseUrl() available to the views
    public function baseUrl($url = null)
    {
        return Utility::baseUrl($url);
    }

    public function __set($name, $value)
    {
        $this->variables[$name] = $value;
    }

    public function __get($name)
    {
        if (isset($this->variables[$name])) {
            return $this->variables[$name];
        } else {
            return null;
        }
    }

    public function __isset($name)
    {
        if (isset($this->variables[$name])) {
            return true;
        } else {
            return false;
        }
    }

}
