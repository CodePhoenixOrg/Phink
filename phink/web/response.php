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
 * Description of response
 *
 * @author david
 */
class TResponse implements \JsonSerializable
{
    
    //put your code here
    private $_data = array();
    private $_token = '';
    protected $scriptsList = [];

    public function redirect($url, $code = 301, $override = true)
    {
        header('Location: ' . $url, $override, $code);
    }
    
    public function getToken()
    {
        return $this->_token;
    }

    public function setToken($token = '')
    {
        $this->_token = $token;
        $this->_data['token'] = $token;
    }

    public function jsonSerialize()
    {
        return $this->_data;
    }

    public function addScript($filename)
    {
        array_push($this->scriptsList, $filename);
    }

//    public function getScripts()
//    {
//        return $this->scriptsList;
//    }

    public function setReturn($value)
    {
        http_response_code($value);
        $this->_data['return'] = $value;
    }

    public function setData($key, $value = '')
    {
        if(is_array($key)) {
//            self::$logger->dump('KEY', $key);
            foreach ($key as $left => $right) {
                $this->_data[$left] = $right;
            }
        } else {
            $this->_data[$key] = (in_array($key, ['view', 'page', 'master'])) ? base64_encode($value) : $value; //, 'page'
        }
            
    }
    
    private function _getHeaders($hsts = false) {
        $result = 200;
        
        header('Origin: ' . SERVER_ROOT);
        header('Access-Control-Expose-Headers: origin');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        
        // Use HTTP Strict Transport Security to force client to use secure connections only

        // iis sets HTTPS to 'off' for non-SSL requests
        if ($hsts && HTTP_PROTOCOL === 'https') {
            header('Strict-Transport-Security: max-age=500; includeSubDomains; preload');

        } elseif ($hsts) {
            
            $request = new TRequest();
            $request->addViewSubRequest('redirect', FULL_SSL_URI, $this->_data);
            
            $res = $request->execSubRequests();
            
            $data = $res['redirect'];
            $code = $data['code'];
            
//            if($code == 200) {
                $result = 200;
                $html = $data['html'];
                $this->_data = json_decode($html);
//            } else {
//                $result = 301;
//                $query_string = http_build_query($this->_data);
//                $uri = FULL_SSL_URI . (!empty($query_string) ? '?' . $query_string : '');
//                $this->redirect($uri);
//            }
            // we are in cleartext at the moment, prevent further execution and output
            //die();
        }        
        
        return $result;
    }
    
    public function sendData()
    {
        if($this->_token) {
            $this->setToken($this->_token);
        }
        header('Content-Type: application/json; charset=UTF-8');
        $code = $this->_getHeaders(false);
        
        if(count($this->scriptsList)) {
            $this->_data['scripts'] = $this->scriptsList;
        }

        if($code === 200) {
            echo json_encode($this);
        }
    }

    public function setMessage($message)
    {
        $this->setData('message', $message);
    }    
}

