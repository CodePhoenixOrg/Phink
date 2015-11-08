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
    protected $scriptList = [];

    public function redirect($url)
    {
        header('Location: ' . $url);
    }
    
    public function jsonSerialize()
    {
        return $this->_data;
    }

    public function addScript($value)
    {
        array_push($this->scriptList, $value);
    }

    public function setToken($token = '')
    {
        $this->_token = $token;
        $this->_data['token'] = $token;
    }

    public function setReturn($value)
    {
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

        $this->_data['scripts'] = $this->scriptList;
                
        header('Content-Type: text/html; charset=UTF-8');
        echo json_encode($this);
    }

    public function setMessage($message)
    {
        $this->setData('message', $message);
    }    
}
