<?php
namespace Phink\Apps\Admin;

use Phink\MVC\TPartialController;
use Phink\Registry\TRegistry;
use Phink\Data\Client\PDO\TPdoConnection;
use Puzzle\Data\Controls as DataControls;
use Puzzle\Menus;
use PDO;

class TTodo extends TPartialController
{
	// tools
    protected $id, $cs, $datacontrols, $conf, $lang, $db_prefix, $query,
        $page_colors, $grid_colors, $panel_colors;
    
    // view fields
    protected $td_title, $td_text, $td_priority, $td_expiry, $td_status, 
        $td_date, $usr_id, $usr_id2, $action;

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
        $this->query = getArgument("query", "SELECT");
        $event = getArgument("event", "onLoad");
        $this->action = getArgument("action", "Ajouter");
        $this->id = getArgument("id", -1);
        $di = getArgument("di", "todo");

        if($this->id === -1) {
            $title_page = $this->menus->retrievePageByDictionaryId($this->conf, $di, $this->lang);
            $this->id = $title_page["index"];
        }

        $tablename = "todo";
        $this->td_id = getArgument("td_id");
        $this->usr_id2 = getArgument("usr_id2");
        if($event === "onLoad" && $this->query === "ACTION") {
            switch ($this->action) {
            case "Ajouter":
    
                $this->td_title="";
                $this->td_text="";
                $this->td_priority="";
                $this->td_expiry="";
                $this->td_status="";
                $this->td_date="";
                $this->usr_id="";
                $this->usr_id2="";
            break;
            case "Modifier":
                $sql="select * from $tablename where td_id='$this->td_id';";
                $stmt = $this->cs->query($sql);
                $rows = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->td_title = $rows["td_title"];
                $this->td_text = $rows["td_text"];
                $this->td_priority = $rows["td_priority"];
                $this->td_expiry = $rows["td_expiry"];
                $this->td_status = $rows["td_status"];
                $this->td_date = $rows["td_date"];
                $this->usr_id = $rows["usr_id"];
                $this->usr_id2 = $rows["usr_id2"];
            break;
            }
        } else if($event === "onRun" && $this->query === "ACTION") {
            switch ($this->action) {
            case "Ajouter":
                $this->td_title = filterPOST("td_title");
                $this->td_text = filterPOST("td_text");
                $this->td_priority = filterPOST("td_priority");
                $this->td_expiry = filterPOST("td_expiry");
                $this->td_status = filterPOST("td_status");
                $this->td_date = filterPOST("td_date");
                $this->usr_id = filterPOST("usr_id");
                $this->usr_id2 = filterPOST("usr_id2");
                $sql = <<<SQL
                insert into $tablename (
                    td_title, 
                    td_text, 
                    td_priority, 
                    td_expiry, 
                    td_status, 
                    td_date, 
                    usr_id,
                    usr_id2
                ) values (
                    :td_title, 
                    :td_text, 
                    :td_priority, 
                    :td_expiry, 
                    :td_status, 
                    :td_date, 
                    :usr_id,
                    :usr_id2
                )
    SQL;
                $stmt = $this->cs->prepare($sql);
                $stmt->execute([':td_title' => $this->td_title, ':td_text' => $this->td_text, ':td_priority' => $this->td_priority, ':td_expiry' => $this->td_expiry, ':td_status' => $this->td_status, ':td_date' => $this->td_date, ':usr_id' => $this->usr_id, ':usr_id2' => $this->usr_id2]);
            break;
            case "Modifier":
                $this->td_title = filterPOST("td_title");
                $this->td_text = filterPOST("td_text");
                $this->td_priority = filterPOST("td_priority");
                $this->td_expiry = filterPOST("td_expiry");
                $this->td_status = filterPOST("td_status");
                $this->td_date = filterPOST("td_date");
                $this->usr_id = filterPOST("usr_id");
                $this->usr_id2 = filterPOST("usr_id2");
                $sql=<<<SQL
                update $tablename set 
                    td_title = :td_title, 
                    td_text = :td_text, 
                    td_priority = :td_priority, 
                    td_expiry = :td_expiry, 
                    td_status = :td_status, 
                    td_date = :td_date, 
                    usr_id = :usr_id,
                    usr_id2 = :usr_id2
                where td_id = '{$this->td_id}';
    SQL;
                $stmt = $this->cs->prepare($sql);
                $stmt->execute([':td_title' => $this->td_title, ':td_text' => $this->td_text, ':td_priority' => $this->td_priority, ':td_expiry' => $this->td_expiry, ':td_status' => $this->td_status, ':td_date' => $this->td_date, ':usr_id' => $this->usr_id, ':usr_id2' => $this->usr_id2]);
            break;
            case "Supprimer":
                $sql = "delete from $tablename where td_id='$this->td_id'";
                $stmt = $this->cs->query($sql);
            break;
            }
            $this->query="SELECT";
        }
    
    }
}