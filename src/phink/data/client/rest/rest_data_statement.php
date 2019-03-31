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

//require_once 'phink/data/data_statement.php';
//require_once 'phink/core/object.php';

use Phink\Core\TObject;
use Phink\Data\IDataStatement;

/**
 * Description of adatareader
 *
 * @author david
 */
class TRestDataStatement extends TObject
{


    private $_statement;
    private $_values;
    private $_fieldCount;
    private $_rowCount;
    private $_meta = array();

    public function __construct($statement)
    {
        $this->_statement = $statement;
    }

    public function getValue($i)
    {
        return $this->_values[$i];
    }

    public function fetch()
    {
        $this->_values = $this->_statement->content;
        return $this->_values;
    }
    
    public function fetchAssoc()
    {
        $this->_values = $this->_statement->content;
        return $this->_values;
    }
    
    public function fetchAll()
    {
        $this->_values = $this->_statement->content;
        return $this->_values;
    }

    public function fetchAllAssoc()
    {
        $this->_values = $this->_statement->content;
        return $this->_values;
    }

    public function fetchObject()
    {
        return $this->_statement->fetchObject();
    }
    
    public function getFieldCount()
    {
        if(!isset($this->_fieldCount)) {
            $this->_fieldCount = $this->_statement->columnCount();
        }
        return $this->_fieldCount;
    }

    public function getRowCount()
    {
        if(!isset($this->_rowCount)) {
            $this->_rowCount = $this->_statement->rowCount();
        }
        return $this->_rowCount;

    }

    public function getFieldName($i)
    {
        if(!isset($this->_meta[$i])) {
            $this->_meta[$i] = $this->_statement->getColumnMeta($i);
        }

        return $this->_meta[$i]['name'];
    }

    public function getFieldNames()
    {
        $result = array();
        $this->getFieldCount();
        for($j = 0; $j < $this->_fieldCount; $j++) {
            array_push($result, $this->_statement->getColumnMeta($j)['name']);
        }
        
        return $result;
    }

    public function getFieldType($i)
    {
        if(!isset($this->_meta[$i])) {
            $this->_meta[$i] = $this->_statement->getColumnMeta($i);
        }

        return $this->_meta[$i]['type'];
    }

    public function getFieldLen($i)
    {
        if(!isset($this->_meta[$i])) {
            $this->_meta[$i] = $this->_statement->getColumnMeta($i);
        }

        return $this->_meta[$i]['len'];
    }

    
}
