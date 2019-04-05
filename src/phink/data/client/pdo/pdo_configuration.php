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
 
 
namespace Phink\Data\Client\PDO;

//require_once 'phink/configuration/data/sqlconfiguration.php';
//require_once 'phink/data/server_type.php';

use Phink\Configuration\Data\TJsonConfiguration;
use Phink\Data\TServerType;
/**
 * Description of mysqlconfiguration
 *
 * @author david
 */
class TPdoConfiguration extends TJsonConfiguration
{
    private $_driver = '';
    private $_host = '';
    private $_databaseName = '';
    private $_user = '';
    private $_password = '';
    private $_port = '';

    public function __construct(string $driver = '', string $databaseName = '', string $host = '', string $user = '', string $password = '', string $port = '')
    {
        $this->_driver = $driver ?? $driver;
        $this->_databaseName = $databaseName ?? $databaseName;
        $this->_host = $host ?? $host;
        $this->_user = $user ?? $user;
        $this->_password = $password ?? $password;
        $this->_port = $port ?? $port;

        $this->canConfigure = false;
    }

    public function configure() : void
    {
        parent::configure();

        $this->_driver = $this->contents['driver'];
        $this->_databaseName = $this->contents['database'];
        $this->_host = $this->contents['host'];
        $this->_user = $this->contents['user'];
        $this->_password = $this->contents['password'];
        $this->_port = $this->contents['port'];
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
