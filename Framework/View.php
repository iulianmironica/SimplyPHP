<?php

/**
 * Description of View
 *
 * @author Iulian Mironica
 */
class View {

    public $controller;
    public $action;
    public $session;
    public $layout;
    public $variables = array();
    public $loadedViews = array();

    /**
     * @param type $controller
     * @param type $action
     */
    public function __construct($params) {
        $this->controller = $params['router']->controller;
        $this->action = $params['router']->action;
        $this->session = $params['session'];

        // Set the default layout
        $this->layout = Util::prepairFileName(FrameworkSettings::VIEW_LAYOUT_FILE, 'View');
    }

    /**
     * @param type $indexOrArray
     * @param type $value
     */
    public function set($indexOrArray, $value = null) {
        if (is_array($indexOrArray) && is_null($value)) {
            array_merge($indexOrArray, $this->variables);
        } else {
            $this->variables[$indexOrArray] = $value;
        }
    }

    /**
     * @param type $layoutFileNameAndLocation
     */
    public function setLayoutFile($layoutFileNameAndLocation) {
        $this->layout = Util::prepairFileName($layoutFileNameAndLocation, 'View');
    }

    /**
     * @param type $variables
     * @param type $layout
     * @param type $return
     * @return type
     */
    public function render($variables = null, $layout = null, $return = false) {

        if (empty($layout) && empty($this->layout)) {
            die('View layout is not set, use: $this->view->setLayoutFile("Layout");');
        }

        $layout = empty($layout) ? $this->layout : Util::prepairFileName($layout, 'View');

        if (is_array($variables)) {
            // Set variables to be visible to the view also
            $this->variables = array_merge($variables, $this->variables);
        }

        $fileNameAndPath = \Util::getPhpFilePath($layout, APPLICATION_VIEW);
        if (!file_exists($fileNameAndPath)) {
            die('View file not found in ' . $fileNameAndPath);
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

    public function __set($name, $value) {
        $this->variables[$name] = $value;
    }

    public function __get($name) {
        return isset($this->variables[$name]) ? $this->variables[$name] : null;
    }

}
