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
 
 
namespace Phink\Data\Client\JSON;

//require_once 'sqlite_data_reader.php';
//require_once 'sqlite_connection.php';
//require_once 'phink/data/command.php';
//require_once 'phink/data/crud_queries.php';
//require_once 'phink/core/object.php';


use Phink\Core\TObject;
use Phink\Data\Client\JSON\TJsonDataReader;
use Phink\Data\ICommand;
use Phink\Data\TCrudQueries;

/**
 * Description of aJsoncommand
 *
 * @author david
 */
class TJsonCommand extends TObject implements ICommand
{
    //put your code here
    private $_fieldCount;
    //private $_rowCount;
    private $_result;
    private $_reader;
    private $_queries;
    private $_activeConnection;
    private $_db = NULL;

    public function __construct(AJsonConnection $activeConnection)
    {
        $this->_activeConnection = $activeConnection;
        $this->_queries = new TCrudQueries();
    }

    public function executeReader()
    {
        $this->_db = $this->_activeConnection->getState();

        $this->_result = $this->_db->query($this->_queries->getSelect());
        $this->_reader = new TJsonDataReader($this->_result);

        return $this->_reader;
    }

    public function executeLimitedReader()
    {
        return $this->executeReaderPage(APage::pageNumber(), APage::pageCount());
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
            $this->_fieldCount = $this->_result->numColumns();
        }
        return $this->_fieldCount;
    }

    public function getRowCount()
    {
        return -1;

    }

    public function fieldName($i)
    {
        return $this->_result->columnName($i);
    }

    public function fieldType($i)
    {
        return $this->_result->columnType($i);
    }

    public function fieldLen($i)
    {
        return -1;
    }

    public function __destruct()
    {
        $this->_result->reset();
    }
}
