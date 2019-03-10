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

//require_once 'phink/data/data_statement.php';
//require_once 'phink/core/object.php';

use Phink\Core\TObject;
use Phink\Data\IDataStatement;

use PDOStatement;

/**
 * Description of adatareader
 *
 * @author david
 */
class TPdoDataStatement extends TObject implements IDataStatement
{


    private $_statement;
    private $_values;
    private $_fieldCount;
    private $_rowCount;
    private $_meta = array();
    private $_colNames = [];
    private $_connection = null;

    public function __construct($statement, $connection = null)
    {
        $this->_statement = $statement;
        //$this->_meta = $statement->getColumnMeta();
        $this->_connection = $connection;
        self::$logger->dump('STATEMENT', $statement);
    }

    public function getValue($i)
    {
        return $this->_values[$i];
    }

    public function fetch()
    {
        $this->_values = $this->_statement->fetch(\PDO::FETCH_NUM);
        return $this->_values;
    }
    
    public function fetchAssoc()
    {
        $this->_values = $this->_statement->fetch(\PDO::FETCH_ASSOC);
        return $this->_values;
    }
    
    public function fetchAll()
    {
        $this->_values = $this->_statement->fetchAll(\PDO::FETCH_NUM);
        return $this->_values;
    }

    public function fetchAllAssoc()
    {
        $this->_values = $this->_statement->fetchAll(\PDO::FETCH_ASSOC);
        return $this->_values;
    }

    public function fetchObject()
    {
        return $this->_statement->fetchObject();
    }
    
    public function getFieldCount()
    {
        if(!isset($this->_fieldCount)) {
            try {
                $this->_fieldCount = $this->_statement->columnCount();
            } catch (\PDOException $ex) {
                if(isset($this->_values[0])) {
                    $this->_fieldCount = count($this->_values[0]);
                } else {
                    throw new \Exception("Cannot count fields of a row before the resource is fetched", -1, $ex);
                }
            }

        }
        return $this->_fieldCount;
    }

    public function getRowCount()
    {
        if(!isset($this->_rowCount)) {
            try {
                $this->_rowCount = $this->_statement->rowCount();
            } catch (\PDOException $ex) {
                if(is_array($this->_values)) {
                    $this->_rowCount = count($this->_values);
                } else {
                    throw new \Exception("Cannot count rows of a result set before the resource is fetched", -1, $ex);
                }
            }
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
        if(count($this->_colNames) == 0 && $this->_connection !== null) {

            $sql = $this->_statement->queryString;
            self::$logger->dump('QUERY STRING', $sql);
            $res = $this->_connection->query($sql);
            $row = $res->fetch(\PDO::FETCH_ASSOC);

            $this->_colNames = array_keys($row);

            // $result = array();
            // $this->getFieldCount();
            // for($j = 0; $j < $this->_fieldCount; $j++) {
            //     array_push($result, $this->_statement->getColumnMeta($j)['name']);
            // }
            // return $result;
    
        }
        return $this->_colNames;        

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
