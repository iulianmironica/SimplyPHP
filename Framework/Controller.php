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
    public $twig;
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

        // Init Twig if enabled
        if (isset(\Application\Settings\Config::$twig['enable']) && \Application\Settings\Config::$twig['enable'] === true) {

            $debug = false;
            if (ENVIRONMENT == 'development') {
                $debug = true;
            }

            $this->twig = new \Application\Library\Twig($debug);
        }
    }

    public static function &getInstance()
    {
        return self::$instance;
    }

    /** Redirects to a local or external url.
     *
     * @param string $uri
     * @param bool $refresh
     * @param int $statusCode
     * @return void
     */
    public function redirect($uri, $refresh = false, $statusCode = 302)
    {
        Utility::redirect($uri, $refresh, $statusCode);
    }

}
