<?php
namespace Phink\Apps\Console;

use Phink\MVC\TController;
use Phink\Registry\TRegistry;

class Console extends TController
{
    protected $console0;
    protected $consoleName = '';
    protected $consoleTitle = '';
    protected $themeBackColor = '';
    protected $themeForeColor = '';

    public function load() : void 
    {
        $this->consoleName = $this->getApplication()->getName();
        $this->consoleTitle = $this->getApplication()->getTitle() . " Console";
        $cookies = $this->getApplication()->getCookie($this->consoleName);

        $theme = isset($cookies['theme']) ? $cookies['theme'] : 'ibm_pc';

        $this->themeBackColor = TRegistry::ini('theme_' . $theme, 'back_color');
        $this->themeForeColor = TRegistry::ini('theme_' . $theme, 'fore_color');
    }
      
}
