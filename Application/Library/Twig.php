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
        // \Twig_Autoloader::register();
        // $loader = new \Twig_Loader_String();
        // $this->twig = new \Twig_Environment($loader);
        // $loader = new \Twig_Loader_String();

        /*
          var_dump(Config::$twig['cache']);
          var_dump(APPLICATION_CACHE);
          exit();
         */

        $loader = new \Twig_Loader_Filesystem(PATH . 'Application\View');
        $this->twig = new \Twig_Environment($loader, array(
            // 'template' => Config::$twig['template'],
            // 'cache' => Config::$twig['cache'],
            'cache' => APPLICATION_CACHE . Config::$twig['cache'],
            'debug' => $debug,
        ));
    }

    function render($file, array $data = array())
    {
        // echo $this->twig->render('Hello {{ name }}!', array('name' => 'SimplyPHP from Twig'));
        echo $this->twig->render($file, array('name' => 'SimplyPHP from Twig'));
    }

}
