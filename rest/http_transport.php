<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Phink\Rest;

/**
 * Description of httpTransport
 *
 * @author David
 */
trait THttpTransport
{
    //put your code here
    protected $request = null;
    protected $response = null;

    public function getRequest()
    {
        return $this->request;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
