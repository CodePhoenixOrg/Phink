<?php

namespace Phink\Data;

//require_once 'phink/core/aobject.php';
use Phink\Core\TObject;

class TSqlParameters extends TObject
{
    public $Host = '';
    public $User = '';
    public $Password = '';
    public $DatabaseName = '';
    public $ServerType = 0;

    public function __construct($host, $user, $password, $databaseName, $serverType)
    {
        $this->Host = $host;
        $this->User = $user;
        $this->Password = $password;
        $this->DatabaseName = $databaseName;
        $this->ServerType = $serverType;
    }

}
