<?php
namespace Phink\Apps\Admin;

use Phink\MVC\TPartialController;
use Phink\Registry\TRegistry;
use Puzzle\Menus;
use Puzzle\Data\Controls as DataControls;
use Puzzle\Controls;
use Phink\Data\Client\PDO\TPdoConnection;

class Mkscript extends TPartialController
{
	// tools
	protected $page, $menus, $conf, $lang, $db_prefix, $scriptMaker;

	// view fields
	protected $hidden, $rad_menu, $rad_dbgrid, $on_change, $on_change_table, $database_list, 
		$table_list, $tab_ides, $tab_mkscript, $bloc_list, $di_name, $di_short, $di_long,
		$pa_filename, $srvdir, $srvfiles, $basedir, $userdb, $filepath;
	
	public function beforeBinding(): void
    {		

        $this->lang = TRegistry::ini('application', 'lang');
        $this->db_prefix = TRegistry::ini('data', 'db_prefix');
		$this->conf = TRegistry::ini('data', 'conf');
		$this->menus = new Menus($this->lang, $this->db_prefix);
        // $this->scriptMaker = new TScriptMaker;

		$database = TRegistry::read('connections', $this->conf)['database'];

		$this->userdb = getArgument('userdb', $database);
		$this->basedir = getArgument('srvdir');
		$usertable = getArgument('usertable');
		$this->pa_filename = getArgument('pa_filename', $usertable);
		$query = getArgument('query', 'MENU');
		$bl_id = getArgument('bl_id', 0);
		$lg = getArgument('lg');

		$datacontrols = new DataControls($lg, $this->db_prefix);
		$controls = new Controls($lg, $this->db_prefix);

		$cs = TPdoConnection::opener($this->conf);
		$tmp_filename = "tmp.php";
		$wwwroot = getCurrentHttpRoot();
		$this->filepath = "$wwwroot/$lg/$this->pa_filename";
		$filedir = "$wwwroot/$lg/";

		$this->tab_ides = $this->menus->getTabIdes($this->conf);

		if ($this->basedir == "") $this->basedir = getCurrentDir() . "/fr";

		$this->on_change = "";
		$this->on_change_table = "";
		$this->srvdir = $controls->create_server_directory_selector("srvdir", "myForm", $this->basedir, $this->on_change);
		$this->srvfiles = $controls->create_server_file_selector("srvfiles", "myForm", $this->basedir, "php", 5, "srvdir", $this->on_change);
		$this->database_list = $datacontrols->createOptionsFromQuery("show databases", 0, 0, array(), $this->userdb, false, $cs);
		$this->table_list = $datacontrols->createOptionsFromQuery("show tables from $this->userdb", 0, 0, array(), $usertable, false, $cs);
		$sql = "select b.bl_id, d.di_fr_short from {$this->db_prefix}blocks b, {$this->db_prefix}dictionary d where b.di_name=d.di_name order by d.di_fr_short";
		$this->block_list = $datacontrols->createOptionsFromQuery($sql, 0, 1, array(), $bl_id, false, $cs);

		//Options de menu
		$this->rad_menu = ['', ''];
		$menu = getArgument('menu', 0);
		$this->rad_menu[$menu] = " checked";

		//Options de script
		$this->rad_dbgrid = ['', ''];
		$dbgrid = getArgument('dbgrid', 0);
		$this->rad_dbgrid[$dbgrid] = " checked";

		//Option de filtre
		$chk_filter = '';
		$filter = getArgument('filter');
		if ($filter == "1") $chk_filter = " checked";

		//Option d'ajout
		$chk_addoption = '';
		$addoption = getArgument('addoption');
		if ($addoption == "1") $chk_addoption = " checked";

		$me_id = getArgument('me_id');

		$this->di_name = (strlen($usertable) > 8) ? substr($usertable, 0, 8) : $usertable;
		$this->di_short = $usertable;
		$this->di_long = "Liste des " . $usertable;

	}
}