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
    private $_parametes = '';

    public function getSelectQuery()
    {
        return (object)['sql' => $this->_select, 'params' => $this->_parametes];
    }
    public function setSelectQuery($value, $params = [])
    {
        $this->_parametes = $params;
        $this->_select = $value;
    }

    public function getInsertQuery()
    {
        return (object)['sql' => $this->_insert, 'params' => $this->_parametes];
    }
    public function setInsertQuery($value, $params = [])
    {
        $this->_parametes = $params;
        $this->_insert = $value;
    }

    public function getUpdateQuery()
    {
        return (object)['sql' => $this->_update, 'params' => $this->_parametes];
    }
    public function setUpdateQuery($value, $params = [])
    {
        $this->_parametes = $params;
        $this->_update = $value;
    }

    public function getDeleteQuery()
    {
        return (object)['sql' => $this->_delete, 'params' => $this->_parametes];
    }
    public function setDeleteQuery($value, $params = [])
    {
        $this->_parametes = $params;
        $this->_delete = $value;
    }

}
