<?php
namespace Phink\Apps\Admin;

use PDO;
use Phink\Data\Client\PDO\TPdoConnection;
use Phink\MVC\TPartialController;
use Phink\Registry\TRegistry;
use Puzzle\Data\Controls as DataControls;
use Puzzle\Menus;

class TDictionary extends TPartialController
{

    // tools
    protected $page_id, $cs, $datacontrols, $conf, $lang, $db_prefix, $query,
        $page_colors, $grid_colors, $panel_colors, $action;

    // view fields
    protected $di_id, $di_name, $di_fr_short, $di_fr_long, $di_en_short, $di_en_long, $di_ru_short, $di_ru_long;

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
        $this->di_id = getArgument('di_id');
        if($event === 'onLoad' && $this->query === 'ACTION') {
            switch ($this->action) {
            case 'Ajouter':
                $this->di_id = '';
                $this->di_name = '';
                $this->di_fr_short = '';
                $this->di_fr_long = '';
                $this->di_en_short = '';
                $this->di_en_long = '';
                $this->di_ru_short = '';
                $this->di_ru_long = '';
                break;
            case 'Modifier':
                $sql = "select * from dictionary where di_id={$this->di_id};";
                self::getLogger()->sql($sql);
                $stmt = $this->cs->query($sql);
                $rows = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->di_id = $rows['di_id'];
                $this->di_name = $rows['di_name'];
                $this->di_fr_short = $rows['di_fr_short'];
                $this->di_fr_long = $rows['di_fr_long'];
                $this->di_en_short = $rows['di_en_short'];
                $this->di_en_long = $rows['di_en_long'];
                $this->di_ru_short = $rows['di_ru_short'];
                $this->di_ru_long = $rows['di_ru_long'];;
            break;
            }
        } else if($event === 'onRun' && $this->query === 'ACTION') {
            switch ($this->action) {
            case 'Ajouter':
                $this->di_id = filterPOST('di_id');
                $this->di_name = filterPOST('di_name');
                $this->di_fr_short = filterPOST('di_fr_short');
                $this->di_fr_long = filterPOST('di_fr_long');
                $this->di_en_short = filterPOST('di_en_short');
                $this->di_en_long = filterPOST('di_en_long');
                $this->di_ru_short = filterPOST('di_ru_short');
                $this->di_ru_long = filterPOST('di_ru_long');;
                $sql = <<<SQL
                insert into dictionary (
                    di_id,
                    di_name,
                    di_fr_short,
                    di_fr_long,
                    di_en_short,
                    di_en_long,
                    di_ru_short,
                    di_ru_long
                ) values (
                    :di_id,
                    :di_name,
                    :di_fr_short,
                    :di_fr_long,
                    :di_en_short,
                    :di_en_long,
                    :di_ru_short,
                    :di_ru_long
                )
SQL;
               $stmt = $this->cs->query($sql, [':di_name' => $this->di_name, ':di_fr_short' => $this->di_fr_short, ':di_fr_long' => $this->di_fr_long, ':di_en_short' => $this->di_en_short, ':di_en_long' => $this->di_en_long, ':di_ru_short' => $this->di_ru_short, ':di_ru_long' => $this->di_ru_long]);
            break;
            case 'Modifier':
                $this->di_id = filterPOST('di_id');
                $this->di_name = filterPOST('di_name');
                $this->di_fr_short = filterPOST('di_fr_short');
                $this->di_fr_long = filterPOST('di_fr_long');
                $this->di_en_short = filterPOST('di_en_short');
                $this->di_en_long = filterPOST('di_en_long');
                $this->di_ru_short = filterPOST('di_ru_short');
                $this->di_ru_long = filterPOST('di_ru_long');
                $sql = <<<SQL
                update dictionary set
                    di_name = :di_name,
                    di_fr_short = :di_fr_short,
                    di_fr_long = :di_fr_long,
                    di_en_short = :di_en_short,
                    di_en_long = :di_en_long,
                    di_ru_short = :di_ru_short,
                    di_ru_long = :di_ru_long
                where di_id = {$this->di_id};
SQL;
                $stmt = $this->cs->query($sql, [':di_name' => $this->di_name, ':di_fr_short' => $this->di_fr_short, ':di_fr_long' => $this->di_fr_long, ':di_en_short' => $this->di_en_short, ':di_en_long' => $this->di_en_long, ':di_ru_short' => $this->di_ru_short, ':di_ru_long' => $this->di_ru_long]);
            break;
            case 'Supprimer':
                $sql = "delete from dictionary where di_id={$this->di_id}";
                $stmt = $this->cs->query($sql);
            break;
            }
            $this->query = 'SELECT';
        }
    }
}