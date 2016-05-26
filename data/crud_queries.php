<?php
namespace Phink\Data;

//require_once 'phink/core/object.php';

/**
 * Description of crudQueries
 *
 * @author david
 */
trait TCrudQueries  {
    //put your code here

    private $_select = '';
    private $_insert = '';
    private $_update = '';
    private $_delete = '';

    public function getSelectQuery()
    {
        return $this->_select;
    }
    public function setSelectQuery($value)
    {
        $this->_select = $value;
    }

    public function getInsertQuery()
    {
        return $this->_insert;
    }
    public function setInsertQuery($value)
    {
        $this->_insert = $value;
    }

    public function getUpdateQuery()
    {
        return $this->_update;
    }
    public function setUpdateQuery($value)
    {
        $this->_update = $value;
    }

    public function getDeleteQuery()
    {
        return $this->_delete;
    }
    public function setDeleteQuery($value)
    {
        $this->_delete = $value;
    }

}
