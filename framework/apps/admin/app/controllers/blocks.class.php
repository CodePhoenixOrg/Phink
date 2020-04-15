<?php
namespace Phink\Apps\Admin;

use PDO;
use Phink\Data\Client\PDO\TPdoConnection;
use Phink\MVC\TPartialController;
use Phink\Registry\TRegistry;
use Puzzle\Data\Controls as DataControls;
use Puzzle\Menus;

class TBlocks extends TPartialController
{

    // tools
    protected $page_id, $cs, $datacontrols, $conf, $lang, $db_prefix, $query,
        $page_colors, $grid_colors, $panel_colors, $action;

    // view fields
    protected $bl_id, $bl_column, $bt_id, $di_id;

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
        $this->bl_id = getArgument('bl_id');
        if($event === 'onLoad' && $this->query === 'ACTION') {
            switch ($this->action) {
            case 'Ajouter':
                $this->bl_id = '';
                $this->bl_column = '';
                $this->bt_id = '';
                $this->di_id = '';
                break;
            case 'Modifier':
                $sql = "select * from blocks where bl_id={$this->bl_id};";
                $stmt = $this->cs->query($sql);
                $rows = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->bl_id = $rows['bl_id'];
                $this->bl_column = $rows['bl_column'];
                $this->bt_id = $rows['bt_id'];
                $this->di_id = $rows['di_id'];;
            break;
            }
        } else if($event === 'onRun' && $this->query === 'ACTION') {
            switch ($this->action) {
            case 'Ajouter':
                $this->bl_id = filterPOST('bl_id');
                $this->bl_column = filterPOST('bl_column');
                $this->bt_id = filterPOST('bt_id');
                $this->di_id = filterPOST('di_id');;
                $sql = <<<SQL
                insert into blocks (
                    bl_id,
                    bl_column,
                    bt_id,
                    di_id
                ) values (
                    :bl_id,
                    :bl_column,
                    :bt_id,
                    :di_id
                )
SQL;
               $stmt = $this->cs->query($sql, [':bl_column' => $this->bl_column, ':bt_id' => $this->bt_id, ':di_id' => $this->di_id]);
            break;
            case 'Modifier':
                $this->bl_id = filterPOST('bl_id');
                $this->bl_column = filterPOST('bl_column');
                $this->bt_id = filterPOST('bt_id');
                $this->di_id = filterPOST('di_id');
                $sql = <<<SQL
                update blocks set
                    bl_column = :bl_column,
                    bt_id = :bt_id,
                    di_id = :di_id
                where bl_id = {$this->bl_id};
SQL;
                $stmt = $this->cs->query($sql, [':bl_column' => $this->bl_column, ':bt_id' => $this->bt_id, ':di_id' => $this->di_id]);
            break;
            case 'Supprimer':
                $sql = "delete from blocks where bl_id={$this->bl_id}";
                $stmt = $this->cs->query($sql);
            break;
            }
            $this->query = 'SELECT';
        }
    }
}