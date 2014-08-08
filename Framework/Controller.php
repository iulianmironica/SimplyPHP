<?php

namespace Framework;

/**
 * Description of BaseController
 *
 * @author Iulian Mironica
 */
abstract class Controller
{

    public $session;
    public $router;
    public $view;
    public static $instance;

    public function __construct($params)
    {
        self::$instance = &$this;

        $router = $params['router'];
        $session = $params['session'];

        $this->router = $router;
        $this->session = $session;

        // Set the view
        $this->view = new View($params);
    }

    public static function &getInstance()
    {
        return self::$instance;
    }

    abstract function index();
}
