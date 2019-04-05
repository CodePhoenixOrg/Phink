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
use Phink\Configuration\TConfiguration;

/**
 * Description of TDataConfiguration
 *
 * @author david
 */
abstract class TDataConfiguration extends TConfiguration
{
    private $_driver = '';
    private $_host = '';
    private $_databaseName = '';
    private $_user = '';
    private $_password = '';
    private $_port = 0;

    public function __construct($driver, $databaseName, $host = '', $user = '', $password = '', $port = 0)
    {
        $this->_driver = $driver;
        $this->_databaseName = $databaseName;
        $this->_host = $host;
        $this->_user = $user;
        $this->_password = $password;
        $this->_port = $port;
    }

    public function configure() : void
    {
    }

    public function getDriver()
    {
        return $this->_driver;
    }
    
    public function getDatabaseName()
    {
        return $this->_databaseName;
    }

    /*
     * Following properties are default null string in constructor because they may not be used (eg: SQLite)
     */
    public function getHost()
    {
        return $this->_host;
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
