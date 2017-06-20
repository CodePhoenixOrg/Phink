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
 
namespace Phink\Core;

use \Phink\Core\TObject;
use \Phink\Web\TWebRouter;
use \Phink\Rest\TRestRouter;


class TRouter extends TObject {

    use \Phink\Web\TWebObject;
   
    protected $apiName = '';
    protected $className = '';
    protected $baseNamespace = '';
    protected $apiFileName = '';
    protected $parameters = [];
    protected $path = '';
    protected $translation = '';
    protected $routes = [];
    protected $requestType = REQUEST_TYPE_WEB;

    public function __construct($parent)
    {
        parent::__construct($parent);
        $this->authentication = $parent->getAuthentication();
        $this->request = $parent->getRequest();
        $this->response = $parent->getResponse();        
    }
    
    public function getTranslation() {
        return $this->translation;
    }
    
    public function getRequestType() {
        return $this->requestType;
    }
    
    public function getParameters() {
        return $this->parameters;
    }
    
    public function getPath() {
        return $this->path;
    }
    
    public function match() {
        $result = REQUEST_TYPE_WEB;
        
        if ($this->routes()) {

            foreach($this->routes as $key=>$value) {
                $result = $key;
                $this->requestType = $key;
                
                $methods = $value;
                $method = strtolower(REQUEST_METHOD);

                if (isset($methods[$method])) {
                    $routes = $methods[$method];
                    $url = REQUEST_URI;
                    foreach($routes as $key=>$value) {
                        $key = str_replace("/", "\/", $key);
                        $matches = \preg_replace('/' . $key . '/', $value, $url);
                        \Phink\Core\TRegistry::getLogger()->debug('URL: ' . $url);
                        \Phink\Core\TRegistry::getLogger()->debug('MATCHES');
                        \Phink\Core\TRegistry::getLogger()->debug($matches);

                        if ($matches !== $url) {
                            $this->requestType = $key;
                            $this->translation = $matches;
                            $baseurl = parse_url($this->translation);
                            
                            $this->path = $baseurl['path'];
                            
                            \Phink\Core\TRegistry::getLogger()->debug('PATH');                            
                            \Phink\Core\TRegistry::getLogger()->debug($this->path);                            
                            
                            $this->parameters = [];
                            if(isset($baseur['query'])) {
                                parse_str($baseurl['query'], $this->parameters);
                            }

                            \Phink\Core\TRegistry::getLogger()->debug('PARAMETERS');                            
                            \Phink\Core\TRegistry::getLogger()->debug($this->parameters);                            
                            
                            return $result;
                        }
                    }
                }

            }
        }

        if ($this->translation === '') {
            $this->requestType = REQUEST_TYPE_WEB;
            $result = REQUEST_TYPE_WEB;
        }

        return $result;
    }

    public function routes() {
        $routesArray = \Phink\Core\TRegistry::item('routes');

        if (count($routesArray) === 0 && file_exists(DOCUMENT_ROOT . 'routes.json')) {
            $routesFile = file_get_contents(DOCUMENT_ROOT . 'routes.json');

            if (strlen($routesFile) === 0) {
                return false;
            }

            $routesArray = json_decode($routesFile, true);
            foreach($routesArray as $key=>$value) {
                \Phink\Core\TRegistry::write('routes', $key, $value);
            }
        }

        $this->routes = $routesArray;
        
        return $routesArray;
    }

    public function translate() {}

    public function dispatch() {}
}


