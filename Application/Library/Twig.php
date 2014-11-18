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
        $loader = new \Twig_Loader_Filesystem(PATH . APPLICATION_VIEW);
        $this->twig = new \Twig_Environment($loader, array(
            'cache' => APPLICATION_CACHE . Config::$twig['cache'],
            'debug' => $debug,
        ));

        // Add the base url
        $this->addBaseUrl('baseUrl');
        // Add queryString method
        $this->twig->addFunction('cteQueryString', new \Twig_SimpleFunction('cteQueryString', function ($newParams) {
            return http_build_query(array_merge($newParams));
        }));
    }

    public function addBaseUrl($name)
    {
        $this->twig->addFunction($name, new \Twig_SimpleFunction($name, function ($url = null) {
            return \Framework\Utility::baseUrl($url);
        }));
    }

    public function render($template, array $data = [])
    {
        $frameworkData = $this->getFrameworkData();
        return $this->twig->render($template, $data + $frameworkData);
    }

    public function getFrameworkData()
    {
        // Get instance
        $fi = \Framework\Controller::getInstance();

        return [
            'session' => $fi->session->data(),
            'router' => $fi->router,
        ];
    }

    public function display($template, array $data = [])
    {
        $frameworkData = $this->getFrameworkData();
        $this->twig->display($template, $data + $frameworkData);
    }

    /*
    private function addFunctions()
    {
        foreach (get_defined_functions() as $functions) {
            foreach ($functions as $function) {
                $this->twig->addFunction($function, new \Twig_Function_Function($function));
            }
        }
    }
    */

}
