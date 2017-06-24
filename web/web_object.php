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
 
 
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Phink\Web;

 /**
 * Description of TObject
 *
 * @author david
 */
 

trait TWebObject {
    
    use THttpTransport;
    
    private static $_currentDirectory;
    private static $_currentFilePath;
    private static $_currentClassName;
    private static $_currentNamespace;
    private static $_sqlConfigurationFileName;
    private static $_pageNumber;
    private static $_pageCount;
    protected $redis = null;
//    protected $response = null;
//    protected $request = null;
    protected $modelFileName = '';
    protected $viewFileName = '';
    protected $controllerFileName = '';
    protected $jsControllerFileName = '';
    protected $cssFileName = '';
    protected $cacheFileName = '';
    protected $preHtmlName = '';
    protected $viewName = '';
    protected $actionName = '';
    protected $className = '';
    protected $namespace = '';
    protected $code = '';
//    protected $authentication = null;
    
//    public function __construct(TObject $parent)
//    {
//        $this->request = $parent->getRequest();
//        $this->response = $parent->getResponse();        
//    }
//

 
    public static function pageNumber($value = null)
    {
        if(isset($value)) {
            self::$_pageNumber = $value;
        }
        else {
            return self::$_pageNumber;
        }
    }

    public static function pageCount($value = null)
    {
        if(isset($value)) {
            self::$_pageCount = $value;
        }
        else {
            return self::$_pageCount;
        }
    }

    public function pageCountByDefault($default)
    {
        self::pageCount($this->request->getQueryArguments(PAGE_COUNT));
        if(!self::pageCount()) {
            self::pageCount($default);
        }

        if($default < 1) {
            self::pageCount(PAGE_COUNT_ZERO);
        }

        return self::pageCount();
    }

    public function getCacheFileName()
    {
        $this->cacheFileName = RUNTIME_DIR . strtolower(str_replace(DIRECTORY_SEPARATOR, '_', $this->controllerFileName));
        return $this->cacheFileName;
    }
    
    public function getPhpCode()
    {

        if(!$this->code) {
//        $this->code = $this->redis->mget($this->getCacheFileName());
//        $this->code = $this->code[0];
            if(file_exists($this->getCacheFileName())) {
                $this->code = file_get_contents($this->getCacheFileName());
            }
        }

        return $this->code;
    }
    
    public function preHtmlExists()
    {
        return file_exists($this->getPreHtmlName());
    }
        
    public function getGlobalDesignName()
    {
        $parts = pathinfo($this->getFileName());
        return DOCUMENT_ROOT . 'tmp' . DIRECTORY_SEPARATOR . $parts['filename'] . '.design.php';
    }

    public function getJsonName()
    {
        
        return RUNTIME_DIR . $this->className . '.json';
    }

    public function getConfigName()
    {
        $parts = pathinfo($this->getFileName());
        return DOCUMENT_ROOT . 'config' . DIRECTORY_SEPARATOR . $parts['filename'] . '.config.' . $parts['extension'];
    }

    public function getXmlName()
    {
        $parts = pathinfo($this->getFileName());
        return DOCUMENT_ROOT . 'tmp' . DIRECTORY_SEPARATOR . $parts['filename'] . '.xml';
    }

    public function getAuthentication()
    {
        return $this->authentication;
    }

    public function setRedis(array $params)
    {
        $this->redis = $params;
    }

    public function getRedis()
    {
        return $this->redis;
    }
    
//    public function getRequest()
//    {
//        return $this->request;
//    }
//    
//    public function getResponse()
//    {
//        return $this->response;
//    }
    
    public function getClassName()
    {
        return $this->className;
    }
    
    public function getActionName()
    {
        return $this->actionName;
    }
    
    public function getFileNamespace()
    {
        return $this->namespace;
    }
    
    public function getRawPhpName()
    {
        return $this->cacheFileName;
    }
    
    public function getModelFileName()
    {
        return $this->modelFileName;
    }

    public function getViewFileName()
    {
        return $this->viewFileName;
    }

    public function getControllerFileName()
    {
        return $this->controllerFileName;
    }    

    public function getJsControllerFileName()
    {
        return $this->jsControllerFileName;
    }    

    public function getCssFileName()
    {
        return $this->cssFileName;
    }    

    public function getViewName()
    {
        return $this->viewName;
    }
    
    public function setViewName()
    {
        $requestUriParts = explode('/', REQUEST_URI);
        $this->viewName = array_pop($requestUriParts);
        $viewNameParts = explode('.',$this->viewName);
        $this->viewName = array_shift($viewNameParts);

        $this->viewName = ($this->viewName == '') ? MAIN_VIEW : $this->viewName;
        $this->className = ucfirst($this->viewName);

//        //self::$logger->debug('VIEW NAME : '  . $this->viewName, __FILE__, __LINE__);
        
    }
    
    public function setNamespace()
    {
        $this->namespace = $this->getFileNamespace();
        
        if(!isset($this->namespace)) {
            $this->namespace = \Phink\TAutoloader::getDefaultNamespace();
        }

    }
    
    public function setNames()
    {
        $this->actionName = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';
        $this->modelFileName = 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . $this->viewName . CLASS_EXTENSION;
        $this->viewFileName = 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $this->viewName . PREHTML_EXTENSION;
        $this->cssFileName = 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $this->viewName . CSS_EXTENSION;
        $this->controllerFileName = 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $this->viewName . CLASS_EXTENSION;
        $this->jsControllerFileName = 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $this->viewName . JS_EXTENSION;
        
        $this->getCacheFileName();

    }

    public function cloneNamesFrom($parent)
    {
        $this->className = $parent->getClassName();
        $this->actionName = $parent->getActionName();
        $this->modelFileName = $parent->getModelFileName();
        $this->viewFileName = $parent->getViewFileName();
        $this->cssFileName = $parent->getCssFileName();
        $this->controllerFileName = $parent->getControllerFileName();
        $this->jsControllerFileName = $parent->getJsControllerFileName();
        
        $this->cacheFileName = $parent->getCacheFileName();

    }

}