<?php
namespace Phink\Apps\Admin;

use PDO;
use Phink\Data\Client\PDO\TPdoConnection;
use Phink\MVC\TPartialController;
use Phink\Registry\TRegistry;
use Puzzle\Data\Controls as DataControls;
use Puzzle\Menus;

class TGroups extends TPartialController
{

    // tools
    protected $page_id, $cs, $datacontrols, $conf, $lang, $db_prefix, $query,
        $page_colors, $grid_colors, $panel_colors, $action;

    // view fields
    protected $grp_id, $grp_name, $grp_members_priv, $grp_menu_priv, $grp_page_priv, $grp_news_priv, $grp_items_priv, $grp_database_priv, $grp_images_priv, $grp_calendar_priv, $grp_newsletter_priv, $grp_forum_priv, $grp_users_priv;

    public function beforeBinding(): void
    {
        $this->lang = TRegistry::ini('application', 'lang');
        $this->db_prefix = TRegistry::ini('data', 'db_prefix');
        $this->conf = TRegistry::ini('data', 'conf');
        $this->datacontrols = new DataControls($this->lang, $this->db_prefix);
        $this->menus = new Menus($this->lang, $this->db_prefix);
        $this->page_colors = (object)TRegistry::ini('page_colors');
        $this->grid_colors = (object)TRegistry::ini('grid_colors');
        $this->panel_colors = (object)TRegistry::ini('panel_colors');

        $this->cs = TPdoConnection::opener('niduslite_conf');
        $this->query = getArgument('query', 'SELECT');
        $event = getArgument('event', 'onLoad');
        $this->action = getArgument('action', 'Ajouter');
        $this->page_id = getArgument('id', -1);
        $this->grp_id = getArgument('grp_id');
        if($event === 'onLoad' && $this->query === 'ACTION') {
            switch ($this->action) {
            case 'Ajouter':
                $this->grp_id = '';
                $this->grp_name = '';
                $this->grp_members_priv = '';
                $this->grp_menu_priv = '';
                $this->grp_page_priv = '';
                $this->grp_news_priv = '';
                $this->grp_items_priv = '';
                $this->grp_database_priv = '';
                $this->grp_images_priv = '';
                $this->grp_calendar_priv = '';
                $this->grp_newsletter_priv = '';
                $this->grp_forum_priv = '';
                $this->grp_users_priv = '';
                break;
            case 'Modifier':
                $sql="select * from groups where grp_id={$this->grp_id};";
                $stmt = $this->cs->query($sql);
                $rows = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->grp_id = $rows['grp_id'];
                $this->grp_name = $rows['grp_name'];
                $this->grp_members_priv = $rows['grp_members_priv'];
                $this->grp_menu_priv = $rows['grp_menu_priv'];
                $this->grp_page_priv = $rows['grp_page_priv'];
                $this->grp_news_priv = $rows['grp_news_priv'];
                $this->grp_items_priv = $rows['grp_items_priv'];
                $this->grp_database_priv = $rows['grp_database_priv'];
                $this->grp_images_priv = $rows['grp_images_priv'];
                $this->grp_calendar_priv = $rows['grp_calendar_priv'];
                $this->grp_newsletter_priv = $rows['grp_newsletter_priv'];
                $this->grp_forum_priv = $rows['grp_forum_priv'];
                $this->grp_users_priv = $rows['grp_users_priv'];;
            break;
            }
        } else if($event === 'onRun' && $this->query === 'ACTION') {
            switch ($this->action) {
            case 'Ajouter':
                $this->grp_id = filterPOST['grp_id'];
                $this->grp_name = filterPOST['grp_name'];
                $this->grp_members_priv = filterPOST['grp_members_priv'];
                $this->grp_menu_priv = filterPOST['grp_menu_priv'];
                $this->grp_page_priv = filterPOST['grp_page_priv'];
                $this->grp_news_priv = filterPOST['grp_news_priv'];
                $this->grp_items_priv = filterPOST['grp_items_priv'];
                $this->grp_database_priv = filterPOST['grp_database_priv'];
                $this->grp_images_priv = filterPOST['grp_images_priv'];
                $this->grp_calendar_priv = filterPOST['grp_calendar_priv'];
                $this->grp_newsletter_priv = filterPOST['grp_newsletter_priv'];
                $this->grp_forum_priv = filterPOST['grp_forum_priv'];
                $this->grp_users_priv = filterPOST['grp_users_priv'];;
                $sql = <<<SQL
                insert into groups (
                    grp_id,
                    grp_name,
                    grp_members_priv,
                    grp_menu_priv,
                    grp_page_priv,
                    grp_news_priv,
                    grp_items_priv,
                    grp_database_priv,
                    grp_images_priv,
                    grp_calendar_priv,
                    grp_newsletter_priv,
                    grp_forum_priv,
                    grp_users_priv
                ) values (
                    :grp_id,
                    :grp_name,
                    :grp_members_priv,
                    :grp_menu_priv,
                    :grp_page_priv,
                    :grp_news_priv,
                    :grp_items_priv,
                    :grp_database_priv,
                    :grp_images_priv,
                    :grp_calendar_priv,
                    :grp_newsletter_priv,
                    :grp_forum_priv,
                    :grp_users_priv
                )
                SQL;
               $stmt = $this->cs->query($sql, [':grp_name' => $this->grp_name, ':grp_members_priv' => $this->grp_members_priv, ':grp_menu_priv' => $this->grp_menu_priv, ':grp_page_priv' => $this->grp_page_priv, ':grp_news_priv' => $this->grp_news_priv, ':grp_items_priv' => $this->grp_items_priv, ':grp_database_priv' => $this->grp_database_priv, ':grp_images_priv' => $this->grp_images_priv, ':grp_calendar_priv' => $this->grp_calendar_priv, ':grp_newsletter_priv' => $this->grp_newsletter_priv, ':grp_forum_priv' => $this->grp_forum_priv, ':grp_users_priv' => $this->grp_users_priv]);
            break;
            case 'Modifier':
                $this->grp_id = filterPOST['grp_id'];
                $this->grp_name = filterPOST['grp_name'];
                $this->grp_members_priv = filterPOST['grp_members_priv'];
                $this->grp_menu_priv = filterPOST['grp_menu_priv'];
                $this->grp_page_priv = filterPOST['grp_page_priv'];
                $this->grp_news_priv = filterPOST['grp_news_priv'];
                $this->grp_items_priv = filterPOST['grp_items_priv'];
                $this->grp_database_priv = filterPOST['grp_database_priv'];
                $this->grp_images_priv = filterPOST['grp_images_priv'];
                $this->grp_calendar_priv = filterPOST['grp_calendar_priv'];
                $this->grp_newsletter_priv = filterPOST['grp_newsletter_priv'];
                $this->grp_forum_priv = filterPOST['grp_forum_priv'];
                $this->grp_users_priv = filterPOST['grp_users_priv'];
                $sql=<<<SQL
                update groups set
                    grp_name = :grp_name
                    grp_members_priv = :grp_members_priv
                    grp_menu_priv = :grp_menu_priv
                    grp_page_priv = :grp_page_priv
                    grp_news_priv = :grp_news_priv
                    grp_items_priv = :grp_items_priv
                    grp_database_priv = :grp_database_priv
                    grp_images_priv = :grp_images_priv
                    grp_calendar_priv = :grp_calendar_priv
                    grp_newsletter_priv = :grp_newsletter_priv
                    grp_forum_priv = :grp_forum_priv
                    grp_users_priv = :grp_users_priv
                where grp_id = {$this->grp_id};
                SQL;
                $stmt = $this->cs->query($sql, [':grp_name' => $this->grp_name, ':grp_members_priv' => $this->grp_members_priv, ':grp_menu_priv' => $this->grp_menu_priv, ':grp_page_priv' => $this->grp_page_priv, ':grp_news_priv' => $this->grp_news_priv, ':grp_items_priv' => $this->grp_items_priv, ':grp_database_priv' => $this->grp_database_priv, ':grp_images_priv' => $this->grp_images_priv, ':grp_calendar_priv' => $this->grp_calendar_priv, ':grp_newsletter_priv' => $this->grp_newsletter_priv, ':grp_forum_priv' => $this->grp_forum_priv, ':grp_users_priv' => $this->grp_users_priv]);
            break;
            case 'Supprimer':
                $sql = "delete from groups where grp_id={$this->grp_id}";
                $stmt = $this->cs->query($sql);
            break;
            }
            $this->query='SELECT';
        }
    }
}