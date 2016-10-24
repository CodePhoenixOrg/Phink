<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Phink\Rest;

/**
 * Description of controller
 *
 * @author David
 */
class TRestController
{
    use THttpTransport;
    //put your code here

    public function __construct(TRestApplication $app)
    {
        $this->authentication = $app->getAuthentication();
        $this->request = $app->getRequest();
        $this->response = $app->getResponse();
    }

    public function head() {}
    public function get() {}
    public function post() {}
    public function put() {}
    public function patch() {}
    public function delete() {}

  
}
