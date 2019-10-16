<?php
namespace Phink\Apps\Admin;

use Puzzle\Menus;
use Puzzle\Design;
use Phink\TAutoloader;
use Phink\Core\IObject;
use Phink\Registry\TRegistry;
use Phink\Web\UI\Widget\UserComponent\TUserComponent;

class TSubPage extends TUserComponent {

    protected
        $menus, $conf, $lang, $db_prefix;

    public function __construct(IObject $parent)
    {
        parent::__construct($parent);

        $this->lang = TRegistry::ini('application', 'lang');
        $this->db_prefix = TRegistry::ini('data', 'db_prefix');
        $database = TRegistry::ini('data', 'database');
        $this->conf = TRegistry::ini('data', 'conf');
        $this->menus = new Menus($this->lang, $this->db_prefix);

    }

    public function render() : void 
    {
        $id = getArgument("id");
        $di = getArgument("di");

        $pages = [
            "mkmain" => "TMakeMain",
            "mkscript" => "TMakeScript",
            "mkfields" => "TMakeFields",
            "mkfile" => "TMakeFile",
            "mkfinal" => "TMakeFinal"
        ];

        if(isset($pages[$di])) {
            $this->setComponentType($pages[$di]);
            parent::render();
            
            return;
        }

        if ($di !== '') {
            $title_page = $this->menus->retrievePageByDictionaryId($this->conf, $di, $this->lang);
            $id = $title_page["index"];
        } else {
            // $title_page = retrievePageByMenuId($conf, $id, $this->lang);
            $title_page = $this->menus->retrievePageById($this->conf, $id, $this->lang);
            $di = $title_page["index"];
        }
        // $this->title = $title_page["title"];
        $page = $this->lang . "/" . $title_page["page"];

        include $page;

    }
}