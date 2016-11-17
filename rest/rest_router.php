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

/**
 * Description of router
 *
 * @author David
 */
class TRestRouter
{
    use THttpTransport;
    
    //put your code here
    private $application = null;
    private $apiName = '';
    private $className = '';
    private $baseNamespace = '';
    private $apiFileName = '';
    private $parameter = '';

    public function __construct(TRestApplication $app)
    {
        $this->application = $app;
        $this->request = $app->getRequest();
        $this->response = $app->getResponse();
    }

    public function translate()
    {
        $nsParts = explode('\\', __NAMESPACE__);
        $this->baseNamespace = array_shift($nsParts);

        $qstring = str_replace('/api/', '', REQUEST_URI);
        $qParts = explode('/', $qstring);
        $this->apiName = array_shift($qParts);
        $this->parameter = array_shift($qParts);
 
//        $this->apiName = preg_replace('/[^a-z0-9_]+/i','', array_shift($qParts));
        $this->className = ucfirst($this->apiName);
        
        $this->apiFileName = DOCUMENT_ROOT . 'app' . DIRECTORY_SEPARATOR . 'rest' . DIRECTORY_SEPARATOR . $this->apiName . '.class.php';
        
        return file_exists($this->apiFileName);
    }

    public function dispatch()
    {
        $data = [];
        $method = REQUEST_METHOD;

        $model = str_replace('rest', 'models', $this->apiFileName);
        if(file_exists($model)) {
            include $model;
        }
        
        $include = \Phink\TAutoloader::includeClass($this->apiFileName, INCLUDE_FILE);
        $fqObject = $include['type'];
        
        \Phink\Log\TLog::debug($fqObject);

        $instance = new $fqObject($this->application);
        
        $request_body = file_get_contents('php://input');
        if(!empty($request_body)) {
            $data = json_decode($request_body, true);
        }
        
        $params = [];
        if(count($data) > 0) {
            $params = array_values($data);
            if($this->parameter !== null) {
                array_unshift($params, $this->parameter);
            }
        } else {
            if($this->parameter !== null) {
                $params = [$this->parameter];
            }
        }
            
        $ref = new \ReflectionMethod($instance, $method);
        if(count($params) > 0) {
            $ref->invokeArgs($instance, $params);
        } else {
            $ref->invoke($instance);
        }
        
        $this->response->sendData();		
    }
}
