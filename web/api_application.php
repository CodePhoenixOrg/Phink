<?php

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
class TApiApplication extends \Phink\Core\TApplication
{
    use \Phink\Web\TApiObject;

    protected $rawPhpName = '';
    protected $className = '';
    protected $namespace = '';
    protected $controllerFileName = '';
    protected $apiName = '';
    protected $actionName = '';
    protected $params = '';

    public function __construct() 
    {
        $this->authentication = new TAuthentication();
        $this->request = new TRequest();
        $this->response = new TResponse();
        
        $this->setApiName();
        $this->setNamespace();
        $this->setNames();
    }
    
    public static function create($params = array())
    {
        (new TApiApplication())->run($params);
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
            || $this->apiName == MAIN_VIEW 
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
        $include = NULL;
        $modelClass = ($include = TAutoloader::includeModelByName($this->apiName)) ? $include['type'] : DEFALT_MODEL;
        $model = new $modelClass();
        
        $include = $this->includeController();
        $controllerClass = $include['type'];
        
        $view = new \Phink\MVC\TView($this);
        
        $controller = new $controllerClass($view, $model);
        $controller->perform();
    }       

    public function includeController()
    {
        $result = false;
        
        $result = TAutoloader::includeClass($this->controllerFileName, RETURN_CODE | INCLUDE_FILE);
        if(!$result) {
            $result = TAutoloader::includeDefaultController($this->namespace, $this->className);
            \Phink\Core\TRegistry::setCode($this->controllerFileName, $result['code']);
        }

        return $result;
    }
    
    
}