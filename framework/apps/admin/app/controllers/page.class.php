<?php

namespace Phink\Apps\Admin;

use Phink\Data\TDataAccess;
use Phink\MVC\TController;
use Puzzle\Menus;
use Puzzle\Design;
use Phink\Registry\TRegistry;

class Page extends TController
{
    protected $img;
    protected $page;
    protected $toplinks;
    protected $main_menu;
    protected $sub_menu;
    protected $title;
    protected $page_colors;
    protected $grid_colors;
    protected $panel_colors;
    protected $di;
    protected $menus;
    protected $conf;
    protected $lang;
    protected $db_prefix;
    protected $userComponent0;

    public function load(): void
    {
        $cs = TDataAccess::getNidusLiteDB();

        $this->lang = TRegistry::ini('application', 'lang');
        $this->db_prefix = TRegistry::ini('data', 'db_prefix');
        $database = TRegistry::ini('data', 'database');
        $this->conf = TRegistry::ini('data', 'conf');
        $this->page_colors = (object)TRegistry::ini('page_colors');
        $this->grid_colors = (object)TRegistry::ini('grid_colors');
        $this->panel_colors = (object)TRegistry::ini('panel_colors');
    
        $_SESSION["javascript"] = "";

        $page_id = getArgument("id", 1);
        $di = getArgument("di");

        $this->menus = new Menus($this->lang, $this->db_prefix);
        $design = new Design;

        if($page_id == 1) {
            $page_id = $this->menus->getPageId($this->conf, "mkmain.php");
        }

        $this->main_menu = $this->menus->createMainMenu($this->conf, 1);
        self::getLogger()->dump('MAIN MENU', $this->main_menu);

        $this->sub_menu = $this->menus->createSubMenu($this->conf, 1, Menus::SUB_MENU_HORIZONTAL);
        $this->toplinks = $this->main_menu["menu"];
        $default_id = $this->main_menu["id"];
        self::getLogger()->dump('TOP LINKS', $this->toplinks);
        self::getLogger()->dump('SUB MENU', $this->sub_menu);
        self::getLogger()->dump('PAGE ID', $default_id);
        
        if ($di !== '') {
            $title_page = $this->menus->retrievePageByDictionaryId($this->conf, $di, $this->lang);
            $page_id = $title_page["id"];
        } else {
            // $title_page = retrievePageByMenuId($conf, $page_id, $this->lang);
            $title_page = $this->menus->retrievePageById($this->conf, $page_id, $this->lang);
            $di = $title_page["id"];
        }

        $this->title = $title_page["title"];
        $this->page = $this->lang . "/" . $title_page["page"];

        self::getLogger()->dump('PAGE', $title_page);
    
        if (!empty($page_colors)) {
            $this->back_color = $page_colors["back_color"];
            $this->text_color = $page_colors["text_color"];
            $this->link_color = $page_colors["link_color"];
            $this->vlink_color = $page_colors["vlink_color"];
            $this->alink_color = $page_colors["alink_color"];
        } else {
            $this->back_color = "red";
            $this->text_color = "black";
            $this->link_color = "black";
            $this->vlink_color = "black";
            $this->alink_color = "black";
        }

        $this->img = REWRITE_BASE . "admin/assets/img";

        //$ses_login=$_SESSION["ses_login"];
        //$authentication=getAuthentication($ses_login);

        //if($authentication) {
        $border_color = "eeeeee";
    }
}
