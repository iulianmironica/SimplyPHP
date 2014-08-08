<?php

namespace Application\Controller;

use Framework\Controller;
use Application\Library\Helper;
use Framework\Input;

/**
 * Description of ServiceController
 *
 * @author Iulian Mironica
 */
class ServiceController extends Controller
{

    private static $responseSuccess = ['status' => 'success'];
    private static $responseError = ['status' => 'error'];

    public function __construct($params)
    {
        parent::__construct($params);

        require_once PATH . DS . FRAMEWORKPATH . 'Input.php';
        require_once PATH . DS . APPLICATION_LIBRARY . 'Helper.php';
        // Raise script time and memory limits
        Helper::raiseTimeAndMemoryLimits();
    }

    public function index()
    {
        if ($this->router->getRequest() === 'GET') {

            $start = microtime(true);
            echo "Completed in ", microtime(true) - $start, " seconds" . PHP_EOL;
        }
    }

    public function save()
    {
        if ($this->router->getRequest() === 'GET') {

            $basket = is_null($this->session->basket) ? array() : $this->session->basket;
            $basket[Input::get('productId')] = array(
                'productId' => Input::get('productId'),
                'classId' => Input::get('classId'),
                'department' => Input::get('department'),
                'product' => Input::get('product'),
                'price' => Input::get('price'),
            );
            $this->session->basket = $basket;

            echo json_encode(self::$responseSuccess);
        }
    }

    public function remove()
    {
        if ($this->router->getRequest() === 'POST') {
            $basket = is_null($this->session->basket) ? array() : $this->session->basket;
            unset($basket[Input::post('productId')]);
            $this->session->basket = $basket;
            // var_dump(Input::post('productId'));
            echo json_encode(self::$responseSuccess);
        }
    }

    public function clear()
    {
        if ($this->router->getRequest() === 'POST') {
            // Clear the basket
            $this->session->basket = null;
            echo json_encode(self::$responseSuccess);
        }
    }

    public function autocomplete()
    {
        $productModel = new ProductModel();
        $query = filter_input(INPUT_GET, 'query', FILTER_SANITIZE_STRING);
        $limit = filter_input(INPUT_GET, 'limit', FILTER_SANITIZE_NUMBER_INT);
        $result = $productModel->getItemsForAutocomplete($query, $limit);
        echo json_encode($result);
    }

}
