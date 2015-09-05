<?php

namespace Application\Library;

require_once PATH . VENDOR_PATH . 'twig/twig/lib/Twig/Autoloader.php';

use Application\Settings\Config;

class Twig
{

    private $twig;
    public $data = [];

    public function __construct($debug = false)
    {
        \Twig_Autoloader::register();

        $loader = new \Twig_Loader_Filesystem(PATH . APPLICATION_VIEW);
        $this->twig = new \Twig_Environment($loader, array(
            'cache' => APPLICATION_CACHE . Config::$twig['cache'],
            'debug' => $debug,
        ));

        // Add the base url
        $this->addBaseUrl('baseUrl');
        // Add queryString method
        $this->twig->addFunction('cteQueryString', new \Twig_SimpleFunction('cteQueryString', function ($newParams) {
            return http_build_query($newParams);
        }));

        // Debug extension - only in development mode
        if ($debug) {
            $this->twig->addExtension(new \Twig_Extension_Debug());
        }

        // Set timezone
        if (!empty(Config::DEFAULT_TIMEZONE)) {
            $this->twig->getExtension('core')->setTimezone(Config::DEFAULT_TIMEZONE);
            // $this->twig->addExtension(new \Twig_Extensions_Extension_Intl());
        }

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
        return $this->twig->render($template, $this->data + $data + $frameworkData);
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
        $this->twig->display($template, $this->data + $data + $frameworkData);
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
