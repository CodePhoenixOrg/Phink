<?php

namespace Phink\Apps\Admin;

use Phink\Core\IObject;
use Phink\Registry\TRegistry;
use Phink\Web\UI\Widget\UserComponent\TUserComponent;
use Puzzle\Menus;

class TSubPage extends TUserComponent
{

    protected $menus, $conf, $lang, $db_prefix;

    public function __construct(IObject $parent)
    {
        parent::__construct($parent);

        $this->lang = TRegistry::ini('application', 'lang');
        $this->db_prefix = TRegistry::ini('data', 'db_prefix');
        $database = TRegistry::ini('data', 'database');
        $this->conf = TRegistry::ini('data', 'conf');
        $this->menus = new Menus($this->lang, $this->db_prefix);
    }

    public function render(): void
    {
        $page_info = [];
        $page_id = $this->getQueryParameters('id');
        $di = $this->getQueryParameters('di');

        if (!empty($page_id)) {
            $page_info = $this->menus->retrievePageById($this->conf, $page_id, $this->lang);
            $di = $page_info["di"];
        } else if (!empty($di)) {
            $page_info = $this->menus->retrievePageByDictionaryId($this->conf, $di, $this->lang);
            $page_id = $page_info["id"];
        }
        $page = CONTROLLER_ROOT . $page_info['page'];
        // }

        if (\file_exists($page)) {
            $this->setComponentInfo($page_info);

            parent::render();
            return;
        }

        if (isset($di)) {
            $this->setComponentType($di);

            parent::render();
            return;
        }
    }
}
