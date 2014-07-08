<?php

/**
 * Description of Main
 *
 * @author Iulian Mironica
 */
class MainController extends \Controller {

    function __construct($params) {
        parent::__construct($params);

        Util::loadClass('ApplicationSettings', APPLICATION_SETTINGS);
        Util::loadClass('ProductModel', APPLICATION_MODEL);

        // A view file can also be set in the constructor
        // $this->view->setLayoutFile('Layout');
    }

    public function index() {

        $productModel = new ProductModel();
        $categoriesAndProducts = $productModel->getProducts();

        $this->view->render(array(
            'content' => 'Main\Main',
            'categoriesAndProducts' => $categoriesAndProducts,
        ));
    }

    public function about() {
        $this->view->render(array(
            'content' => 'Main\About'
        ));
    }

}
