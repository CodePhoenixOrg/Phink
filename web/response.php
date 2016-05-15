<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Phoenix\Web;

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

    public function redirect($url)
    {
        header('Location: ' . $url);
    }
    
    public function setToken($token = '')
    {
        \Phoenix\Log\TLog::debug(__METHOD__ . '::TOKEN::' . $token);
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

    public function setData($key, $value)
    {
        //if(!array_key_exists($key, $this->_data)) {
            
            $this->_data[$key] = (in_array($key, ['view', 'page', 'master'])) ? base64_encode($value) : $value; //, 'page'
            //$this->_data[$key] = $value;
        //}
    }
    
    public function sendData()
    {
        if($this->_token) {
            $this->setToken($this->_token);
        }

        $this->_data['scripts'] = $this->scriptsList;
        
        
        
//        $this->_data['http_host'] = HTTP_HOST;

        header('Content-Type: application/json; charset=UTF-8');
        header('Origin: ' . SERVER_ROOT);
        header('Access-Control-Expose-Headers: origin');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

        echo json_encode($this);
    }

    public function setMessage($message)
    {
        $this->setData('message', $message);
    }    
}

