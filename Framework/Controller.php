<?php

namespace Framework;

/**
 * Description of BaseController
 *
 * @author Iulian Mironica
 */
abstract class Controller
{

    public static $instance;
    public $session;
    public $router;
    public $input;
    public $view;
    public $twig;

    public static function &getInstance()
    {
        return self::$instance;
    }

    abstract function index();

    /** Use dependency injection to make properties from Controller to be
     * available to all controller classes that extend from this.
     *
     * @param \Framework\Router $router
     * @param \Framework\Session $session
     * @param \Framework\Controller $controller
     */
    public function initialize(Router $router, Session $session, Input $input, $controller)
    {
        $this->router = $router;
        $this->session = $session;
        $this->input = $input;

        self::$instance = &$this;

        // Init Twig if enabled
        if (isset(\Application\Settings\Config::$twig['enable']) && \Application\Settings\Config::$twig['enable'] === true) {

            $debug = false;
            if (ENVIRONMENT == 'development') {
                $debug = true;
            }

            $this->twig = new \Application\Library\Twig($debug);
        } else {
            $this->view = new View($router, $session);
        }

        // TODO:
        /*if (!empty(\Application\Settings\Config::$hooks['enable'])) {
            if (!empty(\Application\Settings\Config::$hooks['after'])) {
                call_user_func(\Application\Settings\Config::$hooks['after']);
            }
        }*/

        // Execute the init method from the accessed controller
        if (method_exists($controller, 'init')) {
            $controller->init();
        }
    }

    /** Redirects to a local or external url.
     *
     * @param string $uri
     * @param bool $refresh
     * @param int $statusCode
     * @return void
     */
    public function redirect($uri = '', $refresh = false, $statusCode = 302)
    {
        Utility::redirect($uri, $refresh, $statusCode);
    }

}
