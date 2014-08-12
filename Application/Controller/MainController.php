<?php

namespace Application\Controller;

use Application\Library\KLogger\Logger;
use Framework\Controller;
use Application\Model\ProductModel;

/**
 * Description of Main
 *
 * @author Iulian Mironica
 */
class MainController extends Controller
{

    private static $logger;

    function __construct($params)
    {
        parent::__construct($params);

        // Get the logger from the session
        // $si = Controller::getInstance();
        self::$logger = $this->session->logger;

        // A view file can also be set in the constructor
        // $this->view->setLayoutFile('Layout');
    }

    public function index()
    {
        $productModel = new ProductModel();
        $categoriesAndProducts = $productModel->getProducts();

        $this->view->render(array(
            'content' => 'Main\Main',
            'categoriesAndProducts' => $categoriesAndProducts,
        ));
    }

    public function about()
    {
        $this->view->render(array(
            'content' => 'Main\About'
        ));
    }

    public function log()
    {
        // $logger = new Logger(PATH . APPLICATION_LOG);
        // $si = Controller::getInstance();
        // $logger = $si->session->logger;
        // var_dump($this->session->logger);

        self::$logger->info('Info', array('ok' => 'working'));
        self::$logger->debug('Debug', array('ok' => 'working'));
    }

}
