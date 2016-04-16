<?php

namespace Phoenix\Data;
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IConnection
 *
 * @author david
 */
interface IConnection {
    public function getDriver();
    public function getState();
    public function open();
    public function close();
}
