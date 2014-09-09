<?php

namespace Application\Controller\Testing;

/**
 * Description of ThisController
 *
 * @author Iulian Mironica
 */
class ThisController extends \Framework\Controller
{

    public function index()
    {
        echo 'Controller within subfolder is working properly!';
    }

    public function example()
    {
        $externalRedirectUri = 'http://google.ro';
        $internalRedirectUri = '/main';

        $this->redirect($externalRedirectUri);
    }

}
