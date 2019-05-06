<?php
/*
 * Copyright (C) 2016 David Blanchard
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
 
 namespace Phink\Rest;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Phink\Core\TObject;
/**
 * Description of application
 *
 * @author David
 */
class TRestApplication extends TObject
{
    //put your code here
    use \Phink\Web\THttpTransport;
    
    public static function create(...$params)
    {
        (new TRestApplication())->run();
    }

    public function run()
    {
        $this->authentication = new \Phink\Auth\TAuthentication();
        $this->request = new \Phink\Web\TRequest();
        $this->response = new \Phink\Web\TResponse();
        
        $router = new \Phink\Core\TRouter($this);
        $router->match();
        
        $router = new TRestRouter($router);
        if($router->translate()) {
            $router->dispatch();
        } else {
//            $this->response->sendData(['Error' => "404 : You're searching in the wrong place"]);
            $this->response->setReturn(404);
            
        }
        
    }
}
