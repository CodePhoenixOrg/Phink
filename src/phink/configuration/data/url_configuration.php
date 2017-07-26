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
 
 namespace Phink\Configuration\Data;

//require_once 'phink/data/server_type.php';
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
use Phink\Core\TStaticObject;

/**
 * Description of TDataConfiguration
 *
 * @author david
 */
abstract class TUrlConfiguration extends TStaticObject
{

    private $_method = '';
    private $_url = '';
    private $_user = '';
    private $_password = '';
    private $_port = 0;
    private $_params = [];

    public function __construct($url, $method = 'GET', $user = '', $password = '', $port = 0, $params = [])
    {
        $this->_params = $params;
        $this->_url = $url;
        $this->_method = $method;
        $this->_user = $user;
        $this->_password = $password;
        $this->_port = $port;
    }
    
    public function getMethod()
    {
        return $this->_driver;
    }
    
    public function getDatabaseName()
    {
        return $this->_databaseName;
    }

    public function getUrl()
    {
        return $this->_url;
    }

    public function getUser()
    {
        return $this->_user;
    }

    public function getPassword()
    {
        return $this->_password;
    }

    public function getPort()
    {
        return $this->_port;
    }
    

}
