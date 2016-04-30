<?php

namespace Phoenix\Web;

require_once 'phoenix/core/application.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Phoenix\TAutoloader;
use Phoenix\Auth\TAuthentication;
use Phoenix\MVC\TView;
use Phoenix\Crypto\TCrypto;

/**
 * Description of router
 *
 * @author David
 */
class TWebApplication extends UI\TCustomControl
{
    use \Phoenix\Web\TWebObject;

    protected $rawPhpName = '';
    protected $className = '';
    protected $namespace = '';
    protected $controllerFileName = '';
    protected $viewName = '';
    protected $actionName = '';
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
            $this->namespace = ucfirst($sa[0]);
        }
        $this->namespace .= '\\Controllers'; 
    }

    public function validateToken()
    {
        $result = false;
        // On prend le token en cours ...
        $token = $this->request->getToken();
//        if(!is_string($token) && !isset($_SESSION['USER'])) {
//            $token = '#!';
//            $token = TCrypto::generateToken('');
//        }
        if(is_string($token) || $this->viewName == MAIN_VIEW || $this->viewName == LOGIN_VIEW  || $this->viewName == HOME_VIEW) {
            // on renouvelle le token
        // ... avec ce token on récupère l'utilisateur et un nouveau token
        // de telle sorte qu'on limite la durée de vie du token
            $token = $this->authentication->renewToken($token);
//        $result = TAuthentication::getPermissionByToken($token);
            // on place le nouveau token dans la réponse
            $this->response->setToken($token);
            $result = true;
                    
        } else {
            $this->response->redirect(SERVER_ROOT . MAIN_PAGE);
            //$result = true;
        }
        
        return $result;
    }
 
    public function dispatch()
    {
        if(file_exists($this->cacheFileName)) {
            //\Phoenix\Log\TLog::debug('DISPATCH : ' . $this->cacheFileName);
            //$include = TAutoloader::includeClass($this->cacheFileName, false);
            //include $this->cacheFileName;
            $classText = file_get_contents($this->cacheFileName);
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
        
        $view = new TView($this);
        
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
        
        $result = TAutoloader::includeClass($this->controllerFileName);
        if(!$result) {
            if($this->request->isAJAX() && $this->request->isPartialView()) {
                $result = TAutoloader::includeDefaultPartialController($this->namespace, $this->className);
            } else {
                $result = TAutoloader::includeDefaultController($this->namespace, $this->className);
            }
            \Phoenix\Core\TRegistry::registerCode($this->controllerFileName, $result['code']);
        }

        return $result;
    }
    
    public function includePrimaryController()
    {
        if($this->request->isAJAX() && $this->request->isPartialView()) {
            $result = TAutoloader::includeDefaultController($this->namespace, $this->className);
            \Phoenix\Core\TRegistry::registerCode(strtolower($this->controllerFileName), $result['code']);
        } else {
            $result = $this->includeController();
        }
        
        return $result;
    }
    
}