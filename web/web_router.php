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

 use Phink\Core\TStaticObject;
 use Phink\TAutoloader;
/**
 * Description of router
 *
 * @author David
 */
class TWebRouter extends \Phink\Core\TRouter
{
    private $_isCached = false;

    public function __construct($parent)
    {
        parent::__construct($parent);
        $this->authentication = $parent->getAuthentication();
        $this->request = $parent->getRequest();
        $this->response = $parent->getResponse();
                
        $this->translation = $parent->getTranslation();
        $this->parameters = $parent->getParameters();
        $this->path = $parent->getPath();
        
//        $this->setViewName();        
        

    }

    public function translate()
    {
//        $nsParts = explode('\\', __NAMESPACE__);
//        $this->baseNamespace = array_shift($nsParts);
//
//        $qstring = str_replace('/api/', '', REQUEST_URI);
//        $qParts = explode('/', $qstring);
//        $this->apiName = array_shift($qParts);
//        $this->parameter = array_shift($qParts);
// 
////        $this->apiName = preg_replace('/[^a-z0-9_]+/i','', array_shift($qParts));
//        $this->className = ucfirst($this->apiName);
//        
//        $this->apiFileName = 'app' . DIRECTORY_SEPARATOR . 'rest' . DIRECTORY_SEPARATOR . $this->apiName . CLASS_EXTENSION;
        $requestUriParts = explode('/', $this->path);
        $this->viewName = array_pop($requestUriParts);
        $viewNameParts = explode('.',$this->viewName);
        $this->viewName = array_shift($viewNameParts);

        $this->viewName = ($this->viewName == '') ? MAIN_VIEW : $this->viewName;
        $this->className = ucfirst($this->viewName);
        
        $this->getLogger()->debug('VIEW NAME: ' . $this->viewName);
        
        $this->setNamespace();
        $this->setNames();

        $this->getLogger()->debug('CACHE: ' . $this->cacheFileName);
        $this->getLogger()->debug('VIEW: ' . SITE_ROOT . $this->viewFileName);
        
        
        if(file_exists($this->getCacheFileName())) {
            $this->getLogger()->debug('FROM CACHE: true');
            $this->_isCached = true;
            return true;
        } else {
            $this->getLogger()->debug('FROM CACHE: false');
            return file_exists(SITE_ROOT . $this->viewFileName);
            
        }
        
        
    }

    public function dispatch()
    {
        if($this->_isCached) {
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
            
            return true;
        }

        $include = NULL;
        $modelClass = ($include = TAutoloader::includeModelByName($this->viewName)) ? $include['type'] : DEFALT_MODEL;
        $model = new $modelClass();
        
        $include = $this->includePrimaryController();
        $controllerClass = $include['type'];
        
        $view = new \Phink\MVC\TView($this->parent->getParent());
        
        $controller = new $controllerClass($view, $model);
        if($this->request->isAJAX() && $this->request->isPartialView()) {
            $include = $this->includeController();
            $partialClass = $include['type'];

            $partialController = new $partialClass($controller);

            $partialController->perform();
            
        } else {
            $controller->perform();
        }         
        
//        $data = [];
//        $method = REQUEST_METHOD;
//
//        $model = str_replace('rest', 'models', $this->apiFileName);
//        if(file_exists(SITE_ROOT . $model)) {
//            include SITE_ROOT . $model;
//        }
//        
//        $include = \Phink\TAutoloader::includeClass($this->apiFileName, INCLUDE_FILE);
//        $fqObject = $include['type'];
//        
//        self::$logger->debug($fqObject);
//
//        $instance = new $fqObject($this);
//        
//        $request_body = file_get_contents('php://input');
//        
//        self::getLogger()->debug($request_body);
//        if(!empty($request_body)) {
//            $data = json_decode($request_body, true);
//        }
//        
//        self::getLogger()->debug($data);
////        $params = [];
////        if(count($data) > 0) {
////            $params = array_values($data);
////            if($this->parameter !== null) {
////                array_unshift($params, $this->parameter);
////            }
////        } else {
////            if($this->parameter !== null) {
////                $params = [$this->parameter];
////            }
////        }
//        
//        if(count($data) > 0) {
//            foreach ($data as $key=>$value) {
//                $instance->$key = $value;
//                self::getLogger()->debug("instance->$key = $value");
//            }
//        }
//
//
////        if(count($params) > 0) {
////            $ref->invokeArgs($instance, $params);
////        } else {
////            $ref->invoke($instance);
////        }
//        $instance->$method();
//        
//        $this->response->sendData();		
        return true;
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
    
    public function includeController()
    {
        $result = false;
        
        $result = TAutoloader::includeClass($this->controllerFileName, RETURN_CODE | INCLUDE_FILE);
        if(!$result) {
            if($this->getRequest()->isAJAX() && $this->request->isPartialView()) {
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
        if($this->getRequest()->isAJAX() && $this->request->isPartialView()) {
            $result = TAutoloader::includeDefaultController($this->namespace, $this->className);
            \Phink\Core\TRegistry::setCode(strtolower($this->controllerFileName), $result['code']);
        } else {
            $result = $this->includeController();
        }
        
        return $result;
    }

    
}
