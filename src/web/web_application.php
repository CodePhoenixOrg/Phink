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
 
 
namespace Phink\Web;

//require_once 'phink/core/application.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Phink\TAutoloader;
use Phink\Auth\TAuthentication;
use Phink\Core\TRouter;
use Phink\Rest\TRestRouter;
use Phink\Web\TWebRouter;

/**
 * Description of router
 *
 * @author David
 */
class TWebApplication extends \Phink\Core\TApplication implements IHttpTransport, IWebObject
{
    use \Phink\Web\TWebObject;
    
    protected $rawPhpName = '';
    protected $params = '';

    public function __construct() 
    {
        $this->authentication = new TAuthentication();
        $this->request = new TRequest();
        $this->response = new TResponse();
        
        $this->setViewName();
        $this->setNamespace();
        $this->setNames();
    }
    
    public static function mediaPath() 
    {
        return SERVER_ROOT . '/media/';
    }

    public static function themePath()
    {
        return SERVER_ROOT . '/themes/';
    }

    public static function imagePath()
    {
        return self::mediaPath() . 'images';
    }
    
    public static function create($params = array())
    {
        session_start();
        
        (new TWebApplication())->run($params);
    }

    public function run($params)
    {
        $this->params = $params;
        
        $router = new \Phink\Core\TRouter($this);
        $reqtype = $router->match();

        if($reqtype == REQUEST_TYPE_WEB) {
            if($this->validateToken()) {
                $router = new TWebRouter($router);
            }
        } else {
            $router = new TRestRouter($router);
        }

        if($router->translate()) {
            $this->getLogger()->debug("Ready to dispatch");
            $router->dispatch();

        } else {
            $this->response->setReturn(404);
        }
    }

    public function validateToken()
    {
        $result = false;
        // We get the current token ...
//        $token = $this->request->getToken();
        $token = TRequest::getQueryStrinng('token');
//        if(!is_string($token) && !isset($_SESSION['USER'])) {
//            $token = '#!';
//            $token = TCrypto::generateToken('');
//        }
        if(is_string($token) 
            || $this->viewName == MAIN_VIEW 
            || $this->viewName == MASTER_VIEW 
            || $this->viewName == LOGIN_VIEW  
            || $this->viewName == HOME_VIEW
        ) {
            // We renew the token
            // ... we'll try to match the user with this token and alternatively get a new token
            // so that it can no longer be used
            $token = $this->authentication->renewToken($token);
//        $result = TAuthentication::getPermissionByToken($token);
            // we place the new token in the response
            $this->response->setToken($token);
            $result = true;
                    
        } else {
            $this->response->setReturn(403);
//            $this->response->redirect(SERVER_ROOT . MAIN_PAGE);
            $result = true;
        }
        
        return $result;
    }

}
