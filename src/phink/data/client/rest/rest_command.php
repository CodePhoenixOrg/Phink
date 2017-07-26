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
 
 
namespace Phink\Data\Client\Rest;

//require_once 'phink/core/object.php';
//require_once 'phink/data/command.php';
//require_once 'phink/data/crud_queries.php';
//require_once 'pdo_data_statement.php';
//require_once 'pdo_connection.php';

use Phink\Data\Client\Rest\TRestConnection;
use Phink\Data\Client\Rest\TRestDataStatement;

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of amysqlcommand
 *
 * @author david
 */
class TRestCommand extends \Phink\Data\TCustomCommand
{
    //put your code here
    private $_statement;
    private $_activeConnection;
    private $_connectionHandler;
    private $_commandText;
    
    public function __construct(TRestConnection $activeConnection, $commandText = '')
    {
                
        $this->_activeConnection = $activeConnection;
        $this->_connectionHandler = $this->_activeConnection->getState();
        

        
        if($commandText != '') {
            $this->_commandText = $commandText;
        }
    }

    public function querySelect()
    {
        return $this->query();
    }
    
    public function queryInsert()
    {
        return $this->scalar();
    }

    public function queryUpdate()
    {
        return $this->scalar();
    }

    public function queryDelete()
    {
        return $this->scalar();
    }

    public function addSelectLimit($start, $count)
    {
        // Implement pager here
    }
    
    public function query($sql = '', array $params = null)
    {
        $result = false;
        try {
            $this->_commandText = ($sql != '') ? $sql : $this->_commandText;
        
            $this->_statement = $this->_connectionHandler; 
            
            
            $result = new TRestDataStatement($this->_statement);
        } catch (\Throwable $ex) {
            self::$logger->debug('SQL : ' . $sql . '; params : ' . print_r($params, true), __FILE__, __LINE__);
            self::$logger->exception($ex, __FILE__, __LINE__);
        } catch (\Exception $ex) {
            self::$logger->exception($ex, __FILE__, __LINE__);
        }
        
        return $result;
    }

    public function queryLog($sql = '', array $params = null, $file = __FILE__, $line = __LINE__)
    {
        $result = false;
        try {
            $this->_commandText = ($sql != '') ? $sql : $this->_commandText;
        
            $this->_statement = $this->_connectionHandler; 
            
            $result = new TRestDataStatement($this->_statement);
            
            self::$logger->debug('SQL : ' . $sql . '; params : ' . print_r($params, true), $file, $line);
            
            $result = new TRestDataStatement($this->_statement);
        } catch (\Throwable $ex) {
            self::$logger->debug('SQL : ' . $sql . '; params : ' . print_r($params, true), __FILE__, __LINE__);
            self::$logger->exception($ex, __FILE__, __LINE__);
        } catch (\Exception $ex) {
            self::$logger->exception($ex, $file, $line);
        }
        
        return $result;
    }    
    
    public function scalar($sql = '', array $params = null)
    {
        $result = false;
        return $result;
    }
    
    public function scalarLog($sql = '', array $params = null, $file = __FILE__, $line = __LINE__)
    {
        $result = false;
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
        self::$logger->debug('SQL : ' . $sql, $file, $line);
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
