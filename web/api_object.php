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

trait TApiObject {
    //put your code here
    protected $redis = null;
    protected $response = null;
    protected $request = null;
    protected $modelFileName = '';
    protected $controllerFileName = '';
    protected $jsControllerFileName = '';
    protected $cacheFileName = '';
    protected $actionName = '';
    protected $className = '';
    protected $apiName = '';
    protected $namespace = '';
    protected $code = '';
    protected $method = '';


//    public function __construct(TObject $parent)
//    {
//        $this->request = $parent->getRequest();
//        $this->response = $parent->getResponse();        
//    }
//

 
    public function getCacheFileName()
    {
        $this->cacheFileName = RUNTIME_DIR . str_replace(DIRECTORY_SEPARATOR, '_', $this->controllerFileName);
        return $this->cacheFileName;
    }
    
    public function getPhpCode()
    {

        if(!$this->code) {
//        $this->code = $this->redis->mget($this->getCacheFileName());
//        $this->code = $this->code[0];
            if(file_exists($this->getCacheFileName())) {
                $this->code = file_get_contents(APP_ROOT . $this->getCacheFileName());
            }
        }

        return $this->code;
    }
    
    public function getJsonName()
    {
        return RUNTIME_DIR . $this->className . JSON_EXTENSION;
    }

    public function getConfigName()
    {
        $parts = pathinfo($this->getFileName());
        return DOCUMENT_ROOT . 'config' . DIRECTORY_SEPARATOR . $parts['filename'] . '.config.' . $parts['extension'];
    }

    public function setRedis(array $params)
    {
        $this->redis = $params;
    }

    public function getRedis()
    {
        return $this->redis;
    }
    
    public function getRequest()
    {
        return $this->request;
    }
    
    public function getResponse()
    {
        return $this->response;
    }
    
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
        return $this->apiName;
    }
    
    public function setApiName()
    {
        $requestUriParts = explode('/', REQUEST_URI);
        $this->apiName = array_pop($requestUriParts);
        $apiNameParts = explode('.',$this->apiName);
        $this->apiName = array_shift($apiNameParts);

        $this->apiName = ($this->apiName == '') ? MAIN_VIEW : $this->apiName;
        $this->className = ucfirst($this->apiName);
        $this->method = $_REQUEST['METHOD'];
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
        $this->modelFileName = 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . $this->apiName . CLASS_EXTENSION;
        $this->controllerFileName = 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $this->apiName . DIRECTORY_SEPARATOR . $this->apiName . CLASS_EXTENSION;
        $this->jsControllerFileName = 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $this->apiName . DIRECTORY_SEPARATOR . $this->apiName . JS_EXTENSION;
        
        $this->getCacheFileName();

    }

}