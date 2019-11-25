<?php
namespace Phink\Apps\QBE;

//require PHINK_APPS_ROOT . 'qbe/app_bootstrap.php';

use Phink\MVC\TController;
use Phink\Registry\TRegistry;
use Phink\Data\TDataAccess;

class Qbe extends TController
{
    protected $qbe0;
    protected $qbeName = '';
    protected $qbeTitle = '';
    protected $themeBackColor = '';
    protected $themeForeColor = '';

    public function load() : void 
    {
        TDataAccess::getNidusLiteDB();

        $this->qbeName = $this->getApplication()->getName();
        $this->qbeTitle = $this->getApplication()->getTitle() . " QBE";
        $cookies = $this->getApplication()->getCookie($this->qbeName);

        $theme = isset($cookies['theme']) ? $cookies['theme'] : 'ibm_pc';

        $this->themeBackColor = TRegistry::ini('theme_' . $theme, 'back_color');
        $this->themeForeColor = TRegistry::ini('theme_' . $theme, 'fore_color');
    }
      
}
