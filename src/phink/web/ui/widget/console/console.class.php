<?php
namespace Sol\Controllers;

use Phink\MVC\TController;

class Console extends TController
{
    protected $console0;
    
    public function clearLogs() : void
    {
        // self::getLogger()->clearAll();
        $data = $this->getApplication()->clearLogs();
        $this->getResponse()->setData($data);
    }    
}
