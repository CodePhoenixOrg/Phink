<?php
namespace Phink\Apps\Admin;

use PDO;
use Phink\Data\Client\PDO\TPdoConnection;
use Phink\MVC\TPartialController;
use Phink\Registry\TRegistry;
use Puzzle\Data\Controls as DataControls;
use Puzzle\Menus;

class TChangelog extends TPartialController
{

    // tools
    protected $id, $cs, $datacontrols, $conf, $lang, $db_prefix, $query,
        $page_colors, $grid_colors, $panel_colors, $action;

    // view fields
    protected $cl_id, $cl_title, $cl_text, $cl_date, $cl_time, $app_id, $usr_id;

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
        $this->id = getArgument('id', -1);
        $fieldname = getArgument('cl_id');
        if($event === 'onLoad' && $this->query === 'ACTION') {
            switch ($this->action) {
            case 'Ajouter':
                $this->cl_id = '';
                $this->cl_title = '';
                $this->cl_text = '';
                $this->cl_date = '';
                $this->cl_time = '';
                $this->app_id = '';
                $this->usr_id = '';
                break;
            case 'Modifier':
                $sql="select * from changelog where cl_id='$this->cl_id';";
                $stmt = $this->cs->query($sql);
                $rows = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->cl_id = $rows['cl_id'];
                $this->cl_title = $rows['cl_title'];
                $this->cl_text = $rows['cl_text'];
                $this->cl_date = $rows['cl_date'];
                $this->cl_time = $rows['cl_time'];
                $this->app_id = $rows['app_id'];
                $this->usr_id = $rows['usr_id'];;
            break;
            }
        } else if($event === 'onRun' && $this->query === 'ACTION') {
            switch ($this->action) {
            case 'Ajouter':
                $this->cl_id = filterPOST['cl_id'];
                $this->cl_title = filterPOST['cl_title'];
                $this->cl_text = filterPOST['cl_text'];
                $this->cl_date = filterPOST['cl_date'];
                $this->cl_time = filterPOST['cl_time'];
                $this->app_id = filterPOST['app_id'];
                $this->usr_id = filterPOST['usr_id'];;
                $sql = <<<SQL
                insert into changelog (
                    $this->cl_id,
                    $this->cl_title,
                    $this->cl_text,
                    $this->cl_date,
                    $this->cl_time,
                    $this->app_id,
                    $this->usr_id
                ) values (
                    :cl_id,
                    :cl_title,
                    :cl_text,
                    :cl_date,
                    :cl_time,
                    :app_id,
                    :usr_id
                )
                SQL;
               $stmt = $this->cs->query($sql, [':cl_title' => $this->cl_title, ':cl_text' => $this->cl_text, ':cl_date' => $this->cl_date, ':cl_time' => $this->cl_time, ':app_id' => $this->app_id, ':usr_id' => $this->usr_id]);
            break;
            case 'Modifier':
                $this->cl_id = filterPOST['cl_id'];
                $this->cl_title = filterPOST['cl_title'];
                $this->cl_text = filterPOST['cl_text'];
                $this->cl_date = filterPOST['cl_date'];
                $this->cl_time = filterPOST['cl_time'];
                $this->app_id = filterPOST['app_id'];
                $this->usr_id = filterPOST['usr_id'];
                $sql=<<<SQL
                update changelog set
                    cl_title = :cl_title
                    cl_text = :cl_text
                    cl_date = :cl_date
                    cl_time = :cl_time
                    app_id = :app_id
                    usr_id = :usr_id
                where cl_id = '$this->cl_id';
                SQL;
                $stmt = $this->cs->query($sql, [':cl_title' => $this->cl_title, ':cl_text' => $this->cl_text, ':cl_date' => $this->cl_date, ':cl_time' => $this->cl_time, ':app_id' => $this->app_id, ':usr_id' => $this->usr_id]);
            break;
            case 'Supprimer':
                $sql = "delete from changelog where cl_id='$this->cl_id'";
                $stmt = $this->cs->query($sql);
            break;
            }
            $this->query='SELECT';
        }
    }
}