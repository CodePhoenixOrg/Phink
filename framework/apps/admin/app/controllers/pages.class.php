<?php
namespace Phink\Apps\Admin;

use PDO;
use Phink\Data\Client\PDO\TPdoConnection;
use Phink\MVC\TPartialController;
use Phink\Registry\TRegistry;
use Puzzle\Data\Controls as DataControls;
use Puzzle\Menus;

class TPages extends TPartialController
{

    // tools
    protected $page_id, $cs, $datacontrols, $conf, $lang, $db_prefix, $query,
        $page_colors, $grid_colors, $panel_colors, $action;

    // view fields
    protected $pa_id, $pa_filename, $pa_directory, $pa_url, $di_id, $ft_id, $app_id;

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
        $this->pa_id = getArgument('pa_id');
        if($event === 'onLoad' && $this->query === 'ACTION') {
            switch ($this->action) {
            case 'Ajouter':
                $this->pa_id = '';
                $this->pa_filename = '';
                $this->pa_directory = '';
                $this->pa_url = '';
                $this->di_id = '';
                $this->ft_id = '';
                $this->app_id = '';
                break;
            case 'Modifier':
                $sql = "select * from pages where pa_id={$this->pa_id};";
                $stmt = $this->cs->query($sql);
                $rows = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->pa_id = $rows['pa_id'];
                $this->pa_filename = $rows['pa_filename'];
                $this->pa_directory = $rows['pa_directory'];
                $this->pa_url = $rows['pa_url'];
                $this->di_id = $rows['di_id'];
                $this->ft_id = $rows['ft_id'];
                $this->app_id = $rows['app_id'];;
            break;
            }
        } else if($event === 'onRun' && $this->query === 'ACTION') {
            switch ($this->action) {
            case 'Ajouter':
                $this->pa_id = filterPOST('pa_id');
                $this->pa_filename = filterPOST('pa_filename');
                $this->pa_directory = filterPOST('pa_directory');
                $this->pa_url = filterPOST('pa_url');
                $this->di_id = filterPOST('di_id');
                $this->ft_id = filterPOST('ft_id');
                $this->app_id = filterPOST('app_id');;
                $sql = <<<SQL
                insert into pages (
                    pa_id,
                    pa_filename,
                    pa_directory,
                    pa_url,
                    di_id,
                    ft_id,
                    app_id
                ) values (
                    :pa_id,
                    :pa_filename,
                    :pa_directory,
                    :pa_url,
                    :di_id,
                    :ft_id,
                    :app_id
                )
                SQL;
               $stmt = $this->cs->query($sql, [':pa_filename' => $this->pa_filename, ':pa_directory' => $this->pa_directory, ':pa_url' => $this->pa_url, ':di_id' => $this->di_id, ':ft_id' => $this->ft_id, ':app_id' => $this->app_id]);
            break;
            case 'Modifier':
                $this->pa_id = filterPOST('pa_id');
                $this->pa_filename = filterPOST('pa_filename');
                $this->pa_directory = filterPOST('pa_directory');
                $this->pa_url = filterPOST('pa_url');
                $this->di_id = filterPOST('di_id');
                $this->ft_id = filterPOST('ft_id');
                $this->app_id = filterPOST('app_id');
                $sql = <<<SQL
                update pages set
                    pa_filename = :pa_filename,
                    pa_directory = :pa_directory,
                    pa_url = :pa_url,
                    di_id = :di_id,
                    ft_id = :ft_id,
                    app_id = :app_id
                where pa_id = {$this->pa_id};
                SQL;
                $stmt = $this->cs->query($sql, [':pa_filename' => $this->pa_filename, ':pa_directory' => $this->pa_directory, ':pa_url' => $this->pa_url, ':di_id' => $this->di_id, ':ft_id' => $this->ft_id, ':app_id' => $this->app_id]);
            break;
            case 'Supprimer':
                $sql = "delete from pages where pa_id={$this->pa_id}";
                $stmt = $this->cs->query($sql);
            break;
            }
            $this->query = 'SELECT';
        }
    }
}