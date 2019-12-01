<?php
namespace Phink\Apps\Admin;

use PDO;
use Phink\Data\Client\PDO\TPdoConnection;
use Phink\MVC\TPartialController;
use Phink\Registry\TRegistry;
use Puzzle\Data\Controls as DataControls;
use Puzzle\Menus;

class TBugreport extends TPartialController
{

    // tools
    protected $page_id, $cs, $datacontrols, $conf, $lang, $db_prefix, $query,
        $page_colors, $grid_colors, $panel_colors, $action;

    // view fields
    protected $br_id, $br_title, $br_text, $br_importance, $br_date, $br_time, $bs_id, $usr_id, $app_id;

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
        $this->br_id = getArgument('br_id');
        if($event === 'onLoad' && $this->query === 'ACTION') {
            switch ($this->action) {
            case 'Ajouter':
                $this->br_id = '';
                $this->br_title = '';
                $this->br_text = '';
                $this->br_importance = '';
                $this->br_date = '';
                $this->br_time = '';
                $this->bs_id = '';
                $this->usr_id = '';
                $this->app_id = '';
                break;
            case 'Modifier':
                $sql = "select * from bugreport where br_id={$this->br_id};";
                $stmt = $this->cs->query($sql);
                $rows = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->br_id = $rows['br_id'];
                $this->br_title = $rows['br_title'];
                $this->br_text = $rows['br_text'];
                $this->br_importance = $rows['br_importance'];
                $this->br_date = $rows['br_date'];
                $this->br_time = $rows['br_time'];
                $this->bs_id = $rows['bs_id'];
                $this->usr_id = $rows['usr_id'];
                $this->app_id = $rows['app_id'];;
            break;
            }
        } else if($event === 'onRun' && $this->query === 'ACTION') {
            switch ($this->action) {
            case 'Ajouter':
                $this->br_id = filterPOST('br_id');
                $this->br_title = filterPOST('br_title');
                $this->br_text = filterPOST('br_text');
                $this->br_importance = filterPOST('br_importance');
                $this->br_date = filterPOST('br_date');
                $this->br_time = filterPOST('br_time');
                $this->bs_id = filterPOST('bs_id');
                $this->usr_id = filterPOST('usr_id');
                $this->app_id = filterPOST('app_id');;
                $sql = <<<SQL
                insert into bugreport (
                    br_id,
                    br_title,
                    br_text,
                    br_importance,
                    br_date,
                    br_time,
                    bs_id,
                    usr_id,
                    app_id
                ) values (
                    :br_id,
                    :br_title,
                    :br_text,
                    :br_importance,
                    :br_date,
                    :br_time,
                    :bs_id,
                    :usr_id,
                    :app_id
                )
                SQL;
               $stmt = $this->cs->query($sql, [':br_title' => $this->br_title, ':br_text' => $this->br_text, ':br_importance' => $this->br_importance, ':br_date' => $this->br_date, ':br_time' => $this->br_time, ':bs_id' => $this->bs_id, ':usr_id' => $this->usr_id, ':app_id' => $this->app_id]);
            break;
            case 'Modifier':
                $this->br_id = filterPOST('br_id');
                $this->br_title = filterPOST('br_title');
                $this->br_text = filterPOST('br_text');
                $this->br_importance = filterPOST('br_importance');
                $this->br_date = filterPOST('br_date');
                $this->br_time = filterPOST('br_time');
                $this->bs_id = filterPOST('bs_id');
                $this->usr_id = filterPOST('usr_id');
                $this->app_id = filterPOST('app_id');
                $sql = <<<SQL
                update bugreport set
                    br_title = :br_title,
                    br_text = :br_text,
                    br_importance = :br_importance,
                    br_date = :br_date,
                    br_time = :br_time,
                    bs_id = :bs_id,
                    usr_id = :usr_id,
                    app_id = :app_id
                where br_id = {$this->br_id};
                SQL;
                $stmt = $this->cs->query($sql, [':br_title' => $this->br_title, ':br_text' => $this->br_text, ':br_importance' => $this->br_importance, ':br_date' => $this->br_date, ':br_time' => $this->br_time, ':bs_id' => $this->bs_id, ':usr_id' => $this->usr_id, ':app_id' => $this->app_id]);
            break;
            case 'Supprimer':
                $sql = "delete from bugreport where br_id={$this->br_id}";
                $stmt = $this->cs->query($sql);
            break;
            }
            $this->query = 'SELECT';
        }
    }
}