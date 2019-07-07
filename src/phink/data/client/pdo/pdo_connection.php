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

use Phink\Configuration\TConfiguration;
use Phink\Data\ISqlConnection;
use Phink\Data\TServerType;
use Phink\Data\Client\PDO\TPdoConfiguration;
use Phink\Data\Client\PDO\TPdoDataStatement;
use Phink\Data\TCrudQueries;

class TPdoConnectionException extends \Exception
{
    public function __construct(string $message = "" , int $code = 0, Throwable $previous = NULL)
    {
        parent::__construct($message, $code, $previous);
    }
}

use PDO;
/**
 * Description of TPdoConnection
 *
 * @author david
 */
class TPdoConnection extends TConfiguration implements ISqlConnection
{
    private $_state = null;
    private $_config = null;
    private $_dsn = '';
    private $_params = null;
    private $_statement = null;

    use TCrudQueries;

    public function __construct(TPdoConfiguration $config)
    {
        $this->_config = $config;
        $this->configure();
    }

    public static function builder($filename) : TPdoConnection
    {
        $result = null;
        try {
            $config = new TPdoConfiguration();
            $config->loadConfiguration(APP_DATA . $filename . JSON_EXTENSION);
            $result = new TPdoConnection($config);
    
        } catch (\Exception \PDOException $e) {
            throw new TPdoConnectionException('Something went wrong while building the connection.', 1, $e);
        } finally {
            return $result;
        }
    }

    public function getDriver() : string
    {
        return $this->_config->getDriver();
    }
    
    public function getState() : \PDO
    {
        return $this->_state;
    }
    
    public function getConfiguration() : TPdoConfiguration
    {
        return $this->_config;
    }


    public function querySelect() : ?TPdoDataStatement
    {
        list($sql, $params) = $this->getSelectQuery();
        return $this->query($sql, $params);
    }
    
    public function queryInsert() : int
    {
        return $this->exec($this->getInsertQuery());
    }

    public function queryUpdate() : int
    {
        return $this->exec($this->getUpdateQuery());
    }

    public function queryDelete() : int
    {
        return $this->exec($this->getDeleteQuery());
    }

    public function addSelectLimit($start, $count)
    {
//        $driver = strtolower($this->_activeConnection->getDriver());
//        if(strstr($driver, 'mysql')) {
            $start = (!$start) ? 1 : $start;
            $sql = $this->getSelectQuery() . CR_LF . ' LIMIT ' . (($start - 1) * $count). ', ' . $count . CR_LF;

            $this->setSelectQuery($sql);
//        }
    }

    public function open() : \PDO
    {
        try {
            if($this->_params != null) {
                $this->_state = new \PDO($this->_dsn, $this->_config->getUser(), $this->_config->getPassword(), $this->_params);
            } else {
                $this->_state = new \PDO($this->_dsn, $this->_config->getUser(), $this->_config->getPassword(), []);
            }
        } catch (\PDOException $ex) {
            $this->getlogger()->error($ex, __FILE__, __LINE__);
        }

        return $this->_state;
    }
    
    public function configure() : void
    {
        $this->_dsn = '';
        $this->_params = (array) null;
        if ($this->_config->getDriver() == TServerType::MYSQL) {
            $this->_params = [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"];
            $this->_dsn = $this->_config->getDriver() . ':host=' . $this->_config->getHost() . ';dbname=' . $this->_config->getDatabaseName();
        } elseif($this->_config->getDriver() == TServerType::SQLSERVER) {
            $this->_params = [PDO::SQLSRV_ATTR_ENCODING => PDO::SQLSRV_ENCODING_SYSTEM, PDO::SQLSRV_ATTR_DIRECT_QUERY => true];
            $this->_dsn = $this->_config->getDriver() . ':Server=' . $this->_config->getHost() . ';Database=' . $this->_config->getDatabaseName(); 
        } elseif($this->_config->getDriver() == TServerType::SQLITE) {
            $this->_dsn = $this->_config->getDriver() . ':' . $this->_config->getDatabaseName(); 
        }

    }
    
    public function query(string $sql = '', ?array $params = null)
    {
        $statement = null;
        $result = false;

        try {
            if($params != null) {
                $statement = $this->_state->prepare($sql);
                $statement->execute($params);
            } else {
                $statement = $this->_state->query($sql);
            }
            
            $result = new TPdoDataStatement($statement, $this, $sql);
        } catch (\PDOException $ex) {
            debugLog(__FILE__ . ':' . __LINE__ . ':', ['SQL' => $sql, 'PARAMS' => $params]);
            debugLog(__FILE__ . ':' . __LINE__ . ':', ['exception' => $ex]);
        } catch (\Exception $ex) {
            debugLog(__FILE__ . ':' . __LINE__ . ':', ['exception' => $ex]);
        }
        
        return $result;
    }

    public function exec(string $sql) : ?int
    {
        return $this->_state->exec($sql);
    }

    public function prepare(string $sql) : bool
    {
        return $this->_state->prepare($sql);
    }

    public function beginTransaction() : void
    {
        $this->_state->beginTransaction();
    }
    
    public function commit() : void
    {
        $this->_state->commit();
    }
    
    public function rollback() : void
    {
        $this->_state->rollBack();
    }
    
    public function inTransaction() : void
    {
        $this->_state->inTransaction();
    }
    
    public function lastInsertId() : int
    {
        return $this->_state->lastInsertId();
    }
    
    public function setAttribute(string $key, $value) : void
    {
        $this->_state->setAttribute($key, $value);
    }
    
    public function getAttribute(string $key)
    {
        return $this->_state->getAttribute($key);
    }
    
    public function getLastInsertId() : int
    {
        return $this->_state->lastInsertId();
    }
    
    public function quote(string $value) : string
    {
        return $this->_state->quote($value);
    }

    public function close() : bool
    {
        unset($this->_state);
        return true;
    }

    public function __destruct()
    {
        $this->close();
    }
    
}
