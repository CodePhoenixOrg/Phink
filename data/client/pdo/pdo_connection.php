<?php

namespace Phoenix\Data\Client\PDO;

//require_once 'phoenix/data/connection.php';
//require_once 'phoenix/configuration/configurable.php';
//require_once 'pdo_configuration.php';

use Phoenix\Core\TObject;
use Phoenix\Configuration\IConfigurable;
use Phoenix\Data\IConnection;
use Phoenix\Data\TServerType;
use Phoenix\Data\Client\PDO\TPdoConfiguration;

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
//            if ($this->_sqlConfig->getDriver() == TServerType::MYSQL) {            
//                $this->_state->query("SET NAMES 'utf8'");
//            }
        } catch (\PDOException $ex) {
            \Phoenix\Log\TLog::exception($ex, __FILE__, __LINE__);
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
