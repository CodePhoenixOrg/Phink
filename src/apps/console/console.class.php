<?php
namespace Phink\Apps\Console;

// include PHINK_APPS_ROOT . 'console/bootstrap.php';

use Phink\MVC\TController;
use Phink\Registry\TRegistry;

class Console extends TController
{
    protected $console0;
    protected $consoleName = '';

    public function load() : void 
    {
        $this->consoleName = $this->getApplication()->getTitle() . " Console";
    }
      
}
