<?php
namespace Phink\Configuration\Data;

//require_once 'phink/data/server_type.php';
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
use Phink\Core\TObject;

/**
 * Description of TDataConfiguration
 *
 * @author david
 */
abstract class TDataConfiguration extends TObject
{

    private $_driver = '';
    private $_host = '';
    private $_databaseName = '';
    private $_user = '';
    private $_password = '';
    private $_port = 0;

    public function __construct($driver, $databaseName, $host = '', $user = '', $password = '', $port = 0)
    {
        $this->_driver = $driver;
        $this->_databaseName = $databaseName;
        $this->_host = $host;
        $this->_user = $user;
        $this->_password = $password;
        $this->_port = $port;
    }

    public function getDriver()
    {
        return $this->_driver;
    }
    
    public function getDatabaseName()
    {
        return $this->_databaseName;
    }

    /* 
     * Following properties are default null string in constructor because they may not be used (eg: SQLite)
     */
    public function getHost()
    {
        return $this->_host;
    }

    public function getUser()
    {
        return $this->_user;
    }

    public function getPassword()
    {
        return $this->_password;
    }

    public function getPort()
    {
        return $this->_port;
    }
}
