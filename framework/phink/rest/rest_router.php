<?php
/*
 * Copyright (C) 2019 David Blanchard
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

 use Phink\Core\TStaticObject;
 
/**
 * Description of router
 *
 * @author David
 */
class TRestRouter extends \Phink\Core\TRouter
{
    public function __construct($parent)
    {
        $this->application = $parent->getApplication();
        $this->commands = $this->application->getCommands();
        $this->authentication = $parent->getAuthentication();
        $this->request = $parent->getRequest();
        $this->response = $parent->getResponse(); 
        
        $this->translation = $parent->getTranslation();
        $this->parameters = $parent->getParameters();
        $this->path = $parent->getPath();
    }

    public function translate()
    {
        
        $path = explode('/', $this->getPath());
        
        $className = array_pop($path);
     
        $this->controllerFileName = 'app' . DIRECTORY_SEPARATOR . 'rest' . DIRECTORY_SEPARATOR . $className . CLASS_EXTENSION;
        $this->modelFileName = 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . $className . CLASS_EXTENSION;
        
        // $this->getLogger()->debug('REST CONTROLLER: ' . SRC_ROOT . $this->className);
        
        return file_exists(SRC_ROOT . $this->controllerFileName);
    }

    public function dispatch()
    {
        $data = [];
        $method = REQUEST_METHOD;

        if(file_exists(SRC_ROOT . $this->modelFileName)) {
            include SRC_ROOT . $this->modelFileName;
        }
        
        list($controllerFileName, $className, $code) = \Phink\TAutoloader::includeClass($this->controllerFileName, INCLUDE_FILE);
        include $controllerFileName;
        
        $instance = new $className($this);
        
        $request_body = file_get_contents('php://input');
        
        // self::getLogger()->debug($request_body);
        if(!empty($request_body)) {
            $data = json_decode($request_body, true);
        }
        
        // self::getLogger()->debug($data);
        
        if(count($this->getParameters()) === 0) {
            $this->parameters = [];
        }
        
        if(count($data) > 0) {
            $this->parameters = array_merge($this->getParameters(), $data);
        }
//        $params = [];
//        if(count($data) > 0) {
//            $params = array_values($data);
//            if($this->parameter !== null) {
//                array_unshift($params, $this->parameter);
//            }
//        } else {
//            if($this->parameter !== null) {
//                $params = [$this->parameter];
//            }
//        }
        
        if(count($this->parameters) > 0) {
            foreach ($this->parameters as $key=>$value) {
                $instance->$key = $value;
                // self::getLogger()->debug("instance->$key = $value");
            }
        }


//        if(count($params) > 0) {
//            $ref->invokeArgs($instance, $params);
//        } else {
//            $ref->invoke($instance);
//        }
        $instance->$method();
        
        $this->getResponse()->sendData();		
    }
}
