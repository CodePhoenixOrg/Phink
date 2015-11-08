<?php

namespace Phoenix\Data;
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author david
 */
interface IDataStatement {
    public function fetch();
    public function fetchAll();
    public function fetchObject();
    public function getFieldCount();
    public function getRowCount();
    public function getFieldName($i);
    public function getFieldType($i);
    public function getFieldLen($i);
}
?>
