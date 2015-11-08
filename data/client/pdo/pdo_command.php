<?php

namespace Phoenix\Data\Client\PDO;

//require_once 'phoenix/core/object.php';
//require_once 'phoenix/data/command.php';
//require_once 'phoenix/data/crud_queries.php';
//require_once 'pdo_data_statement.php';
//require_once 'pdo_connection.php';

use Phoenix\Data\Client\PDO\TPdoConnection;
use Phoenix\Data\Client\PDO\TPdoDataStatement;

use PDO;
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of amysqlcommand
 *
 * @author david
 */
class TPdoCommand extends \Phoenix\Data\TCustomCommand
{
    //put your code here
    private $_statement;
    private $_activeConnection;
    private $_connectionHandler;
    private $_commandText;
    
    public function __construct(TPdoConnection $activeConnection, $commandText = '')
    {
                
        $this->_activeConnection = $activeConnection;
        $this->_connectionHandler = $this->_activeConnection->getState();
        $this->_connectionHandler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        if($commandText != '') {
            $this->_commandText = $commandText;
        }
    }

    public function querySelect()
    {
        return $this->query($this->getSelectQuery());
    }
    
    public function queryInsert()
    {
        return $this->exec($this->getInsertQuery());
    }

    public function queryUpdate()
    {
        return $this->exec($this->getUpdateQuery());
    }

    public function queryDelete()
    {
        return $this->exec($this->getDeleteQuery());
    }

    public function addSelectLimit($start, $count)
    {
        $start = (!$start) ? 1 : $start;
        $this->setSelectQuery($this->getSelectQuery() . ' LIMIT ' . (($start - 1) * $count). ', ' . $count);
    }
    
    public function query($sql = '', array $params = null)
    {
        $result = false;
        try {
            $this->_commandText = ($sql != '') ? $sql : $this->_commandText;
        
            if($params != null) {
                $this->_statement = $this->_connectionHandler->prepare($this->_commandText);
                $this->_statement->execute($params);
            } else {
                $this->_statement = $this->_connectionHandler->query($this->_commandText);
            }
            
            $result = new TPdoDataStatement($this->_statement);
        } catch (\Exception $ex) {
            \Phoenix\Log\TLog::exception($ex, __FILE__, __LINE__);
        }
        
        return $result;
    }

    public function queryLog($sql = '', array $params = null, $file = __FILE__, $line = __LINE__)
    {
        $result = false;
        try {
            $this->_commandText = ($sql != '') ? $sql : $this->_commandText;
        
            if($params != null) {
                $this->_statement = $this->_connectionHandler->prepare($this->_commandText);
                $this->_statement->execute($params);
            } else {
                $this->_statement = $this->_connectionHandler->query($this->_commandText);
            }
            
            \Phoenix\Log\TLog::debug('SQL : ' . $sql . '; params : ' . print_r($params, true), $file, $line);
            
            $result = new TPdoDataStatement($this->_statement);
        } catch (\Exception $ex) {
            \Phoenix\Log\TLog::exception($ex, $file, $line);
        }
        
        return $result;
    }    
    
    public function scalar($sql = '', array $params = null)
    {
        $result = false;
        try {
            $this->_commandText = ($sql != '') ? $sql : $this->_commandText;
        
            if($params != null) {
                $this->_statement = $this->_connectionHandler->prepare($this->_commandText);
                $this->_statement->execute($params);
            } else {
                $this->_statement = $this->_connectionHandler->query($this->_commandText);
            }
            
            $stmt = new TPdoDataStatement($this->_statement);
            if($row = $stmt->fetch()) {
                $result = $row[0];
            }
        } catch (\Exception $ex) {
            \Phoenix\Log\TLog::exception($ex, __FILE__, __LINE__);
        }
        
        return $result;
    }
    
    public function scalarLog($sql = '', array $params = null, $file = __FILE__, $line = __LINE__)
    {
        $result = false;
        try {
            $this->_commandText = ($sql != '') ? $sql : $this->_commandText;
        
            if($params != null) {
                $this->_statement = $this->_connectionHandler->prepare($this->_commandText);
                $this->_statement->execute($params);
            } else {
                $this->_statement = $this->_connectionHandler->query($this->_commandText);
            }
            
            \Phoenix\Log\TLog::debug('SQL : ' . $sql . '; params : ' . print_r($params, true), $file, $line);
            
            $stmt = new TPdoDataStatement($this->_statement);
            if($row = $stmt->fetch()) {
                $result = $row[0];
            }
        } catch (\Exception $ex) {
            \Phoenix\Log\TLog::exception($ex, $file, $line);
        }
        
        return $result;
    }    

    public function exec($sql = '')
    {
        $this->_commandText = ($sql != '') ? $sql : $this->_commandText;
        $this->_commandText = ($sql != '') ? $sql : $this->_commandText;
        
        return $this->_connectionHandler->exec($this->_commandText);
    }

    public function execLog($sql = '', $file = __FILE__, $line = __LINE__)
    {
        \Phoenix\Log\TLog::debug('SQL : ' . $sql, $file, $line);
        $this->_commandText = ($sql != '') ? $sql : $this->_commandText;

        return $this->_connectionHandler->exec($this->_commandText);
    }

    public function getCommandText()
    {
        return $this->_commandText;
    }

    public function setCommandText($value)
    {
        $this->_commandText = $value;
    }

    public function getActiveConnection()
    {
        return $this->_activeConnection;
    }

    public function getStatement()
    {
        return $this->_statement;
    }

    public function closeCursor()
    {
        $this->_statement->closeCursor();
    }
    
    public function __destruct()
    {
        unset($this->_statement);
        unset($this->_connectionHandler);
    }
}
