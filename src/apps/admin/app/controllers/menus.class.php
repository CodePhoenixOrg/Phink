<?php
namespace Phink\Apps\Admin;

use PDO;
use Phink\Data\Client\PDO\TPdoConnection;
use Phink\MVC\TPartialController;
use Phink\Registry\TRegistry;
use Puzzle\Data\Controls as DataControls;
use Puzzle\Menus;

class TMenus extends TPartialController
{

    // tools
    protected $page_id, $cs, $datacontrols, $conf, $lang, $db_prefix, $query,
        $page_colors, $grid_colors, $panel_colors, $action;

    // view fields
    protected $me_id, $me_level, $me_target, $pa_id, $bl_id;

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
        $this->me_id = getArgument('me_id');
        if($event === 'onLoad' && $this->query === 'ACTION') {
            switch ($this->action) {
            case 'Ajouter':
                $this->me_id = '';
                $this->me_level = '';
                $this->me_target = '';
                $this->pa_id = '';
                $this->bl_id = '';
                break;
            case 'Modifier':
                $sql="select * from menus where me_id={$this->me_id};";
                $stmt = $this->cs->query($sql);
                $rows = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->me_id = $rows['me_id'];
                $this->me_level = $rows['me_level'];
                $this->me_target = $rows['me_target'];
                $this->pa_id = $rows['pa_id'];
                $this->bl_id = $rows['bl_id'];;
            break;
            }
        } else if($event === 'onRun' && $this->query === 'ACTION') {
            switch ($this->action) {
            case 'Ajouter':
                $this->me_id = filterPOST['me_id'];
                $this->me_level = filterPOST['me_level'];
                $this->me_target = filterPOST['me_target'];
                $this->pa_id = filterPOST['pa_id'];
                $this->bl_id = filterPOST['bl_id'];;
                $sql = <<<SQL
                insert into menus (
                    me_id,
                    me_level,
                    me_target,
                    pa_id,
                    bl_id
                ) values (
                    :me_id,
                    :me_level,
                    :me_target,
                    :pa_id,
                    :bl_id
                )
                SQL;
               $stmt = $this->cs->query($sql, [':me_level' => $this->me_level, ':me_target' => $this->me_target, ':pa_id' => $this->pa_id, ':bl_id' => $this->bl_id]);
            break;
            case 'Modifier':
                $this->me_id = filterPOST['me_id'];
                $this->me_level = filterPOST['me_level'];
                $this->me_target = filterPOST['me_target'];
                $this->pa_id = filterPOST['pa_id'];
                $this->bl_id = filterPOST['bl_id'];
                $sql=<<<SQL
                update menus set
                    me_level = :me_level
                    me_target = :me_target
                    pa_id = :pa_id
                    bl_id = :bl_id
                where me_id = {$this->me_id};
                SQL;
                $stmt = $this->cs->query($sql, [':me_level' => $this->me_level, ':me_target' => $this->me_target, ':pa_id' => $this->pa_id, ':bl_id' => $this->bl_id]);
            break;
            case 'Supprimer':
                $sql = "delete from menus where me_id={$this->me_id}";
                $stmt = $this->cs->query($sql);
            break;
            }
            $this->query='SELECT';
        }
    }
}