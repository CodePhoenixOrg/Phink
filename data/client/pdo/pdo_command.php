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

//require_once 'phink/core/object.php';
//require_once 'phink/data/command.php';
//require_once 'phink/data/crud_queries.php';
//require_once 'pdo_data_statement.php';
//require_once 'pdo_connection.php';

use Phink\Data\Client\PDO\TPdoConnection;
use Phink\Data\Client\PDO\TPdoDataStatement;

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
class TPdoCommand extends \Phink\Data\TCustomCommand
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
        //if($this->_connectionHandler instanceof \PDO) {
            $this->_connectionHandler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//        } else {
//            \Phink\Log\TLog::debug('_connectionHandler', $this->_connectionHandler);
//            throw new \Exception('PDO Connection is null ! Please check the parameters.');
//        }
        
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
//        $driver = strtolower($this->_activeConnection->getDriver());
//        if(strstr($driver, 'mysql')) {
            $start = (!$start) ? 1 : $start;
            //$sql = str_replace(PHX_MYSQL_LIMIT, ' LIMIT ' . (($start - 1) * $count). ', ' . $count, $this->getSelectQuery());
            $sql = $this->getSelectQuery() . CR_LF . ' LIMIT ' . (($start - 1) * $count). ', ' . $count . CR_LF;

            $this->setSelectQuery($sql);
//        }
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
        } catch (\PDOException $ex) {
            \Phink\Log\TLog::debug('SQL : ' . $sql . '; params : ' . print_r($params, true), $file, $line);
            \Phink\Log\TLog::exception($ex, __FILE__, __LINE__);
        } catch (\Exception $ex) {
            \Phink\Log\TLog::exception($ex, __FILE__, __LINE__);
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
            
            \Phink\Log\TLog::debug('SQL : ' . $sql . '; params : ' . print_r($params, true), $file, $line);
            
            $result = new TPdoDataStatement($this->_statement);
        } catch (\Exception $ex) {
            \Phink\Log\TLog::exception($ex, $file, $line);
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
            \Phink\Log\TLog::exception($ex, __FILE__, __LINE__);
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
            
            \Phink\Log\TLog::debug('SQL : ' . $sql . '; params : ' . print_r($params, true), $file, $line);
            
            $stmt = new TPdoDataStatement($this->_statement);
            if($row = $stmt->fetch()) {
                $result = $row[0];
            }
        } catch (\Exception $ex) {
            \Phink\Log\TLog::exception($ex, $file, $line);
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
        \Phink\Log\TLog::debug('SQL : ' . $sql, $file, $line);
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
