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

    abstract function index();

    /** Use dependency injection to make properties from Controller to be
     * available to all controller classes that extend from this.
     *
     * @param \Framework\Router $router
     * @param \Framework\Session $session
     * @param type $controller
     */
    public function initialize(Router $router, Session $session, $controller)
    {
        $this->router = $router;
        $this->session = $session;

        self::$instance = &$this;

        $this->view = new View($router, $session);

        // Execute the init method from the accessed controller
        if (method_exists($controller, 'init')) {
            $controller->init();
        }
    }

    public static function &getInstance()
    {
        return self::$instance;
    }

}
