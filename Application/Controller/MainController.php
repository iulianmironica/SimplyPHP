<?php

namespace Application\Controller;

use Framework\Controller;
use Application\Model\ProductModel;
use \Application\Library\Twig;

/**
 * Description of MainController
 *
 * @author Iulian Mironica
 */
class MainController extends Controller
{

    private static $logger;

    public function init()
    {
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

    public function ok()
    {
        // self::$logger->info('Enter');
        // $this->twig->render('Main/ok.html.twig', ['name' => 'SimplyPHP']);

        $this->twig->display('Main/ok.html.twig', ['name' => 'SimplyPHP']);

        // self::$logger->info('Exit');
    }

}
