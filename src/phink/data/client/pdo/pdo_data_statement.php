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
use Phink\Data\TServerType;
use Phink\Data\IDataStatement;
use Phink\Data\Client\PDO\TPdoConfiguration;
use Phink\Data\Client\PDO\TPdoConnection;
use Phink\Data\CLient\PDO\Mapper\TPdoMySQLDataTypesMapper;
use Phink\Data\CLient\PDO\Mapper\TPdoSQLiteDataTypesMapper;

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
    private $_typesMapper = null;
    private $_config = null;
    private $_connection = null;
    private $_native_connection = null;
    private $_sql = '';
    private $_driver;

    public function __construct($statement, TPdoConnection $connection = null, $sql = '')
    {
        $this->_statement = $statement;
        $this->_sql = $sql;

        if($connection !== null) {
            $this->_connection = $connection;
            $this->_native_connection = $connection->getState();
            $this->_config = $connection->getConfiguration();
            $this->_driver = $this->_config->getDriver();
            $this->_setTypesMapper();
        } 
    }

    public function getValue($i)
    {
        return $this->_values[$i];
    }

    public function fetch($mode = \PDO::FETCH_NUM)
    {
        $this->_values = $this->_statement->fetch($mode);
        return $this->_values;
    }
    
    public function fetchAll($mode = \PDO::FETCH_NUM)
    {
        $this->_values = $this->_statement->fetchAll($mode);
        return $this->_values;
    }
    
    public function fetchAssoc()
    {
        $this->_values = $this->_statement->fetch(\PDO::FETCH_ASSOC);
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

    public function getFieldNames()
    {
        if(count($this->_colNames) == 0 && $this->_connection !== null) {

            $sql = $this->_statement->queryString;
            $res = $this->_native_connection->query($sql);
            $row = $res->fetch(\PDO::FETCH_ASSOC);

            $this->_colNames = array_keys($row);
        }

        return $this->_colNames;        
    }

    public function getFieldName($i)
    {
        $name = '';

        if ($this->_typesMapper !== null) {
            $info = $this->_typesMapper->getInfo($i);
            $name = $info->name;
        } else {
            if (!isset($this->_meta[$i])) {
                $this->_meta[$i] = $this->_statement->getColumnMeta($i);
            }
            $name = $this->_meta[$i]['name'];
        }

        return $name;
    }

    public function getFieldType($i)
    {
        $type = '';

        if ($this->_typesMapper !== null) {
            $info = $this->_typesMapper->getInfo($i);
            $type = $info->type;
        } else {
            if (!isset($this->_meta[$i])) {
                $this->_meta[$i] = $this->_statement->getColumnMeta($i);
            }
            $type = $this->_meta[$i]['native_type'];
        }

        return $type;
    }

    public function getFieldLen($i)
    {
        $len = 0;

        if ($this->_typesMapper !== null) {
            $info = $this->_typesMapper->getInfo($i);
            $len = $info->length;
        } else {
            if (!isset($this->_meta[$i])) {
                $this->_meta[$i] = $this->_statement->getColumnMeta($i);
            }
            $len = $this->_meta[$i]['len'];
        }

        return $len;
    }

    public function typeNumToName($type)
    {
        return $this->_typesMapper->typeNumToName($type);
    }

    public function typeNameToPhp($type)
    {
        return $this->_typesMapper->typeNameToPhp($type);
    }

    public function typeNumToPhp($type)
    {
        return $this->_typesMapper->typeNumToPhp($type);
    }

    private function _setTypesMapper()
    {
        if ($this->_driver === TServerType::MYSQL) {
            $this->_typesMapper = new TPdoMySQLDataTypesMapper($this->_config, $this->_sql);
        }
        if ($this->_driver === TServerType::SQLITE) {
            $this->_typesMapper = new TPdoSQLiteDataTypesMapper($this->_config, $this->_sql);
        }
    }    
}
