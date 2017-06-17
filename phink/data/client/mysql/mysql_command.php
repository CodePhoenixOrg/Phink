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
 
 
namespace Phink\Data\Client\MySQL;

//require_once 'phink/core/object.php';
//require_once 'phink/data/command.php';
//require_once 'phink/data/crud_queries.php';
//require_once 'mysql_data_reader.php';
//require_once 'mysql_connection.php';

use Phink\Core\TObject;
use Phink\Web\TRequest;
use Phink\Data\ICommand;
use Phink\Data\TCrudQueries;
use Phink\Data\Client\MySQL\TMySqlConnection;
use Phink\Data\Client\MySQL\TMySqlDataReader;
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of amysqlcommand
 *
 * @author david
 */
class TMySqlCommand extends TObject implements ICommand
{
    //put your code here
    private $_fieldCount;
    private $_rowCount;
    private $_result;
    private $_reader;
    private $_queries;
    private $_activeConnection;

    public function __construct(TMySqlConnection $activeConnection)
    {
        $this->_activeConnection = $activeConnection;
        $this->_queries = new TCrudQueries();
    }

    public function executeReader()
    {
        $this->_result = mysql_query($this->_queries->getSelect(), $this->_activeConnection->getState());
        $this->_reader = new TMySqlDataReader($this->_result);
        //$this->setFieldCount();
        //$this->setRowCount();

        return $this->_reader;
    }

    public function executeLimitedReader()
    {
        return $this->executeReaderPage(TRequest::pageNumber(null), TRequest::pageCount(null));
    }

    public function executeReaderPage($page, $count)
    {
        $start = ($page - 1) * $count;
        $this->_queries->setSelect($this->_queries->getSelect() . " LIMIT $start, $count");
        
        return $this->executeReader();
    }

    public function executeNonQuery()
    {

    }

    public function getQueries()
    {
        return $this->_queries;
    }

    public function getActiveConnection()
    {
        return $this->_activeConnection;
    }

    public function getReader()
    {
        return $this->_reader;
    }
    
    public function getFieldCount()
    {
        if(!isset($this->_fieldCount)) {
            $this->_fieldCount = mysql_num_fields($this->_result);
        }
        return $this->_fieldCount;
    }

    public function getRowCount()
    {
        if(!isset($this->_rowCount)) {
            $this->_rowCount = mysql_num_rows($this->_result);
        }
        return $this->_rowCount;

    }

    public function fieldName($i)
    {
        return mysql_field_name($this->_result, $i);
    }

    public function fieldType($i)
    {
        return mysql_field_type($this->_result, $i);
    }

    public function fieldLen($i)
    {
        return mysql_field_len($this->_result, $i);
    }

    public function __destruct()
    {
        mysql_free_result($this->_result);
    }
}

?>
