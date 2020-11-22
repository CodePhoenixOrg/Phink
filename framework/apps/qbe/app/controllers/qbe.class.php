<?php
namespace Phink\Apps\QEd;

//require PHINK_APPS_ROOT . 'qed/app_bootstrap.php';

use Phink\MVC\TController;
use Phink\Registry\TRegistry;
use Phink\Data\TDataAccess;

class QEd extends TController
{
    protected $qed0;
    protected $qedName = '';
    protected $qedTitle = '';
    protected $themeBackColor = '';
    protected $themeForeColor = '';

    public function load() : void 
    {
        $cs = TDataAccess::getNidusLiteDB();

        $this->qedName = $this->getApplication()->getName();
        $this->qedTitle = $this->getApplication()->getTitle() . " QEd";
        $cookies = $this->getApplication()->getCookie($this->qedName);

        $theme = isset($cookies['theme']) ? $cookies['theme'] : 'ibm_pc';

        $this->themeBackColor = TRegistry::ini('theme_' . $theme, 'back_color');
        $this->themeForeColor = TRegistry::ini('theme_' . $theme, 'fore_color');
    }
      
}
