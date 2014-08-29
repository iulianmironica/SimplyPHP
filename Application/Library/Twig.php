<?php

namespace Application\Library;

require_once PATH . VENDOR_PATH . 'autoload.php';

// require_once PATH . VENDOR_PATH . 'twig\twig\lib\Twig\Autoloader.php';

use Application\Settings\Config;

class Twig
{

    private $twig;

    public function __construct($debug = false)
    {
        $loader = new \Twig_Loader_Filesystem(PATH . 'Application\View');
        $this->twig = new \Twig_Environment($loader, array(
            'cache' => APPLICATION_CACHE . Config::$twig['cache'],
            'debug' => $debug,
        ));

        // Add the base url
        $this->addBaseUrl('baseUrl');
    }

    public function render($template, array $data = array())
    {
        $data['session'] = $this->getSessionData();
        return $this->twig->render($template, $data);
    }

    public function display($template, array $data = array())
    {
        $data['session'] = $this->getSessionData();
        $this->twig->display($template, $data);
    }

    private function addFunctions()
    {
        foreach (get_defined_functions() as $functions) {
            foreach ($functions as $function) {
                $this->twig->addFunction($function, new \Twig_Function_Function($function));
            }
        }
    }

    public function getSessionData()
    {
        // Get instange
        $fi = \Framework\Controller::getInstance();
        // Get session data
        return $fi->session->data();
    }

    public function addBaseUrl($name)
    {
        $this->twig->addFunction($name, new \Twig_SimpleFunction($name, function($url = null) {
            return \Framework\Utility::baseUrl($url);
        }));
    }

}
