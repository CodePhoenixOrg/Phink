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


namespace Phink\Data\Client\PDO;

//require_once 'phink/configuration/data/sqlconfiguration.php';
//require_once 'phink/data/server_type.php';

use Phink\Configuration\Data\TJsonConfiguration;
use Phink\Data\TServerType;
use Phink\Registry\TRegistry;

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

    public function __construct(?string $driver = '', ?string $databaseName = '', ?string $host = '', ?string $user = '', ?string $password = '', ?string $port = '')
    {
        $this->_driver = $driver;
        $this->_databaseName = $databaseName;
        $this->_host = $host;
        $this->_user = $user;
        $this->_password = $password;
        $this->_port = $port;

        $this->canConfigure = false;
    }

    public function loadConfiguration(string $confname): bool
    {
        $result = false;

        if (file_exists($confname)) {
            $result = parent::loadConfiguration($confname);

            return $result;
        }

        if (TRegistry::exists('connections', $confname)) {
            $this->canConfigure = false;
            $this->contents = TRegistry::read('connections', $confname);

            $this->configure();

            $result = true;
        }

        return $result;
    }

    public function configure(): void
    {
        if ($this->canConfigure) {
            parent::configure();
        }

        $this->_driver = $this->contents['driver'];
        $this->_databaseName = $this->contents['database'];
        if($this->_driver == TServerType::SQLITE) {
            $this->_databaseName = APP_DATA . $this->_databaseName;
        }
        $this->_host = isset($this->contents['host']) ? $this->contents['host'] : '';
        $this->_user = isset($this->contents['user']) ? $this->contents['user'] : '';
        $this->_password = isset($this->contents['password']) ? $this->contents['password'] : '';
        $this->_port = isset($this->contents['port']) ? $this->contents['port'] : '';
    }

    public function getDriver(): string
    {
        return $this->_driver;
    }

    public function getDatabaseName(): string
    {
        return $this->_databaseName;
    }

    /*
     * Following properties are default null string in constructor because they may not be used (eg: SQLite)
     */
    public function getHost(): string
    {
        return $this->_host;
    }

    public function getUser(): string
    {
        return $this->_user;
    }

    public function getPassword(): string
    {
        return $this->_password;
    }

    public function getPort(): string
    {
        return $this->_port;
    }
}
