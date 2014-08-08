<?php

namespace Application\Controller;

use Framework\Logger;
use Framework\Controller;
use Application\Model\ProductModel;

/**
 * Description of Main
 *
 * @author Iulian Mironica
 */
class MainController extends Controller
{

    function __construct($params)
    {
        require_once PATH . DS . APPLICATION_MODEL . 'ProductModel.php';
        parent::__construct($params);

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
        $logger = new Logger();
        $logger->error(array('ok' => 'Testing'));
    }

}
