<?php

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
        $this->_values = $this->_statement->fetchAll();
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
