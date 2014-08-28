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
    }

    public function render($file, array $data = array())
    {
        $this->twig->render($file, $data);
    }

}
