<?php

/**
 * Description of Main
 *
 * @author Iulian Mironica
 */
class MainController extends \Controller {

    function __construct($params) {
        parent::__construct($params);

        Util::loadClass('ProductModel', APPLICATION_MODEL);

        // A view file can also be set in the constructor
        // $this->view->setLayoutFile('Layout');
    }

    public function index() {

        /* Parse the ini config file
         * -------------------------
        $ini = parse_ini_file(APPLICATION_SETTINGS.'Configuration.ini');
        var_dump($ini);
        exit();
         */

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

    public function ok() {
        $logger = new Logger();
        $logger->error(array('ok' => 'kkkkkkkkkkkkkkkkkkkkkkkk'));
        echo date(Constants::DATE_TYPE);
    }

}
