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

require_once 'phink/core/application.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Phink\TAutoloader;
use Phink\Auth\TAuthentication;
use Phink\Crypto\TCrypto;

/**
 * Description of router
 *
 * @author David
 */
class TWebApplication extends \Phink\Core\TApplication
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
        (new TWebApplication())->run($params);
    }

    public function run($params)
    {
        session_start();
        
        $this->params = $params;
        if($this->validateToken()) {
            $this->dispatch();
        }
    }


    public function setNamespace()
    {
        if(strstr(SERVER_NAME, 'localhost')) {
            $this->namespace = CUSTOM_NAMESPACE;
        } else {
            $sa = explode('.', SERVER_NAME);
            array_pop($sa);
            if(count($sa) == 2) {
                array_shift($sa);
            }
            $this->namespace = str_replace('-', '_', ucfirst($sa[0]));
        }
        $this->namespace .= '\\Controllers'; 
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
            || $this->viewName == 'sol'
            || $this->viewName == 'info'
            || $this->viewName == 'xml'
            || $this->viewName == 'notepad'
            || $this->viewName == 'mail'

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
            $this->response->redirect(SERVER_ROOT . MAIN_PAGE);
            $result = true;
        }
        
        return $result;
    }
 
    public function dispatch()
    {
        if(file_exists(APP_ROOT . $this->cacheFileName)) {
            //\Phink\Log\TLog::debug('DISPATCH : ' . $this->cacheFileName);
            //$include = TAutoloader::includeClass($this->cacheFileName, false);
            //include $this->cacheFileName;
            $classText = file_get_contents(APP_ROOT . $this->cacheFileName);
            include $this->cacheFileName;
            
            $classText = str_replace("\r", '', $classText);
            $classText = str_replace("\n", '', $classText);

            $start = strpos($classText, 'namespace');
            $namespace = '';
            if ($start > 0) {
                $start += 10;
                $end = strpos($classText, ';', $start);
                $namespace = substr($classText, $start, $end - $start);
                $className = $namespace . '\\' . $this->className;
            }
            //$className = $include['type'];
            $class = new $className($this);
            $class->perform();
            return;
        }

        $include = NULL;
        $modelClass = ($include = TAutoloader::includeModelByName($this->viewName)) ? $include['type'] : DEFALT_MODEL;
        $model = new $modelClass();
        
        $include = $this->includePrimaryController();
        $controllerClass = $include['type'];
        
        $view = new \Phink\MVC\TView($this);
        
        $controller = new $controllerClass($view, $model);
        if($this->request->isAJAX() && $this->request->isPartialView()) {
            $include = $this->includeController();
            $partialClass = $include['type'];

            $partialController = new $partialClass($controller);

            $partialController->perform();
            
        } else {
            $controller->perform();
        }         
    }

    public function includeController()
    {
        $result = false;
        
        $result = TAutoloader::includeClass($this->controllerFileName, RETURN_CODE | INCLUDE_FILE);
        if(!$result) {
            if($this->request->isAJAX() && $this->request->isPartialView()) {
                $result = TAutoloader::includeDefaultPartialController($this->namespace, $this->className);
            } else {
                $result = TAutoloader::includeDefaultController($this->namespace, $this->className);
            }
            \Phink\Core\TRegistry::setCode($this->controllerFileName, $result['code']);
        }

        return $result;
    }
    
    public function includePrimaryController()
    {
        if($this->request->isAJAX() && $this->request->isPartialView()) {
            $result = TAutoloader::includeDefaultController($this->namespace, $this->className);
            \Phink\Core\TRegistry::setCode(strtolower($this->controllerFileName), $result['code']);
        } else {
            $result = $this->includeController();
        }
        
        return $result;
    }
    
}