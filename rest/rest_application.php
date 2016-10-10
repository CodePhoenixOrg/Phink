<?php
namespace Phink\Rest;

include 'core.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of application
 *
 * @author David
 */
class TRestApplication
{
    //put your code here
    use THttpTransport;
    
    public static function create()
    {
        (new TRestApplication())->run();
    }

    public function run()
    {
        $this->request = new \Phink\Web\TRequest();
        $this->response = new \Phink\Web\TResponse();
        
        $router = new TRestRouter($this);
        if($router->translate()) {
            $router->dispatch();
        } else {
            $this->response->setReturn(404);
            $this->response->sendData(['Error' => "404 : You're searching in the wrong place"]);
            
        }
        
    }
}
