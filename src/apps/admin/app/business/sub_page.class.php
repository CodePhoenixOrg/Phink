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
        $page_id = $this->getQueryParameters('id');
        $di = $this->getQueryParameters('di');

        if (!empty($page_id)) {
            $title_page = $this->menus->retrievePageById($this->conf, $page_id, $this->lang);
            $di = $title_page["id"];
        } else if (!empty($di)) {
            $title_page = $this->menus->retrievePageByDictionaryId($this->conf, $di, $this->lang);
            $page_id = $title_page["id"];
        }

        self::getLogger()->debug('ID::' . $page_id);
        self::getLogger()->debug('DI::' . $di);
        self::getLogger()->dump('PAGE iNFO::', $title_page);

        // $page = SITE_ROOT . $this->getDirName() . DIRECTORY_SEPARATOR . APP_DIR . 'pages' . DIRECTORY_SEPARATOR . $title_page["page"];
        // if(!\file_exists($page)) {
            $page = DOCUMENT_ROOT . $this->lang . DIRECTORY_SEPARATOR . $title_page["page"];
        // }

        if(\file_exists($page)) {
            include $page;

            return;
        }

        if (isset($di)) {
            $this->setComponentType($di);

            parent::render();

            return;
        }
    }
}
