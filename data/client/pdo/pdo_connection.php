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

//require_once 'phink/data/connection.php';
//require_once 'phink/configuration/configurable.php';
//require_once 'pdo_configuration.php';

use Phink\Core\TObject;
use Phink\Configuration\IConfigurable;
use Phink\Data\IConnection;
use Phink\Data\TServerType;
use Phink\Data\Client\PDO\TPdoConfiguration;

use PDO;
/**
 * Description of TPdoConnection
 *
 * @author david
 */
class TPdoConnection extends TObject implements IConnection, IConfigurable
{

    private $_state = 0;
    private $_sqlConfig;
    private $_dsn;
    private $_params;

    public function __construct(TPdoConfiguration $sqlConfig)
    {
        $this->_sqlConfig = $sqlConfig;
        $this->configure();
    }

    public function getDriver()
    {
        return $this->_sqlConfig->getDriver();
    }
    
    public function getState()
    {
        return $this->_state;
    }

    public function open()
    {
        try {
            if($this->_params != null) {
                $this->_state = new \PDO($this->_dsn, $this->_sqlConfig->getUser(), $this->_sqlConfig->getPassword(), $this->_params);
            } else {
                $this->_state = new \PDO($this->_dsn, $this->_sqlConfig->getUser(), $this->_sqlConfig->getPassword());
            }
        } catch (\PDOException $ex) {
            \Phink\Log\TLog::exception($ex, __FILE__, __LINE__);
        }

        return $this->_state;
    }

    public function configure()
    {
        $this->_dsn = '';
        $this->_params = (array) null;
        if ($this->_sqlConfig->getDriver() == TServerType::MYSQL) {
            $this->_params = [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"];
            $this->_dsn = $this->_sqlConfig->getDriver() . ':host=' . $this->_sqlConfig->getHost() . ';dbname=' . $this->_sqlConfig->getDatabaseName();
        } elseif($this->_sqlConfig->getDriver() == TServerType::SQLSERVER) {
            $this->_params = [PDO::SQLSRV_ATTR_ENCODING => PDO::SQLSRV_ENCODING_SYSTEM, PDO::SQLSRV_ATTR_DIRECT_QUERY => true];
            $this->_dsn = $this->_sqlConfig->getDriver() . ':Server=' . $this->_sqlConfig->getHost() . ';Database=' . $this->_sqlConfig->getDatabaseName(); 
        } elseif($this->_sqlConfig->getDriver() == TServerType::SQLITE) {
            $this->_dsn = $this->_sqlConfig->getDriver() . ':' . $this->_sqlConfig->getDatabaseName(); 
        }

    }
    
    public function beginTransaction()
    {
        $this->_state->beginTransaction();
    }
    
    public function commit()
    {
        $this->_state->commit();
    }
    
    public function rollback()
    {
        $this->_state->rollBack();
    }
    
    public function inTransaction()
    {
        $this->_state->inTransaction();
    }
    
    public function lastInsertId()
    {
        return $this->_state->lastInsertId();
    }
    
    public function setAttribute($key, $value)
    {
        $this->_state->setAttribute($key, $value);
    }
    
    public function getAttribute($key)
    {
        return $this->_state->getAttribute($key);
    }
    
    public function getLastInsertId()
    {
        return $this->_state->lastInsertId();
    }
    
    public function close()
    {
        unset($this->_state);

        return true;
    }

    public function __destruct()
    {
        $this->close();
    }
    
    //public function 
}
