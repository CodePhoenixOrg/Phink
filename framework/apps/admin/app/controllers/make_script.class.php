<?php
namespace Phink\Apps\Admin;

use Phink\MVC\TPartialController;
use Phink\Registry\TRegistry;
use Puzzle\Menus;
use Puzzle\Data\Controls as DataControls;
use Puzzle\Controls;
use Phink\Data\Client\PDO\TPdoConnection;

class TMakeScript extends TPartialController
{
	// tools
	protected $page, $menus, $workconf, $workdb, $userdb, $userconf, $lang, $db_prefix;

	// view fields
	protected $hidden, $rad_menu, $rad_dbgrid, $on_change, $on_change_table, $database_list, $conf_list, $menu_list,
		$table_list, $tab_ides, $tab_mkscript, $bloc_list, $di_name, $di_short, $di_long,
		$pa_filename, $srvdir, $srvfiles, $basedir, $filepath, $chk_filter, $chk_addoption;
	
	public function beforeBinding(): void
    {		

        $this->lang = TRegistry::ini('application', 'lang');
        $this->db_prefix = TRegistry::ini('data', 'db_prefix');
		$this->menus = new Menus($this->lang, $this->db_prefix);
		$lg = getArgument('lg', $this->lang);

		$this->workconf = TRegistry::ini('data', 'conf');
		$this->userconf = getArgument('userconf', $this->workconf);

		$this->workdb = TRegistry::read('connections', $this->workconf)['database'];
		$this->userdb = TRegistry::read('connections', $this->userconf)['database'];
		// $this->userdb = getArgument('userdb', $this->userdb);

		self::getLogger()->debug('WORK CONF::' . $this->workconf);
		self::getLogger()->debug('USER CONF::' . $this->userconf);
		self::getLogger()->debug('USER DB::' . $this->userdb);

		$this->basedir = getArgument('srvdir', getCurrentDir() . DIRECTORY_SEPARATOR . $lg);
		$usertable = getArgument('usertable');
		// $this->pa_filename = getArgument('pa_filename', $usertable);
		$this->pa_filename = $usertable;
		$this->query = getArgument('query', 'MENU');
		$bl_id = getArgument('bl_id', 0);

		$datacontrols = new DataControls($lg, $this->db_prefix);
		$controls = new Controls($lg, $this->db_prefix);

		$workcs = TPdoConnection::opener($this->workconf);
		$usercs = TPdoConnection::opener($this->userconf);

		$tmp_filename = "tmp.php";
		$filedir = DOCUMENT_ROOT . $lg . DIRECTORY_SEPARATOR;
		$this->filepath = $filedir . $this->pa_filename;

		$this->tab_ides = $this->menus->getTabIdes($this->workconf);

		$confs = TRegistry::keys('connections');

		$stmt = $usercs->showTables();
		$tables = $stmt->fetchAll();

		$this->on_change = "";
		$this->on_change_table = "";
		$this->srvdir = $controls->create_server_directory_selector("srvdir", "myForm", $this->basedir, $this->on_change);
		$this->srvfiles = $controls->create_server_file_selector("srvfiles", "myForm", $this->basedir, "php", 5, "srvdir", $this->on_change);
		//$this->database_list = $datacontrols->createOptionsFromQuery("show databases", 0, 0, array(), $this->userdb, false, $workcs);
		$this->conf_list = $datacontrols->createOptionsFromArray($confs, '', 0, 0, [$this->userconf], $this->userconf, false);
		$this->table_list = $datacontrols->createOptionsFromArray($tables, '', 0, 0, [$usertable], $usertable, false);
		$sql = <<<SQL
		SELECT 
			b.bl_id, d.di_{$this->lang}_short
		FROM
			blocks b
				INNER JOIN
			dictionary d ON d.di_id = b.di_id
		ORDER BY d.di_{$this->lang}_short
SQL;
		$this->block_list = $datacontrols->createOptionsFromQuery($sql, 0, 1, array(), $bl_id, false, $workcs);

		//Options de menu
		$menu_level = [];
		array_push($menu_level, ['0' => 'Invisible']);
		array_push($menu_level, ['1' => 'Menu principal']);
		array_push($menu_level, ['2' => 'Sous-menu']);

		$this->rad_menu = ['', ''];
		$me_level = getArgument('me_level', '0');
		$this->rad_menu[$me_level] = " checked";
		$this->menu_list = $datacontrols->createOptionsFromArray($menu_level, "", 0, 0, [$me_level], $me_level, false);

		//Options de script
		$this->rad_dbgrid = ['', ''];
		$dbgrid = getArgument('dbgrid', '0');
		$this->rad_dbgrid[$dbgrid] = " checked";

		//Option de filtre
		$this->chk_filter = '';
		$filter = getArgument('filter');
		if ($filter == "1") $this->chk_filter = " checked";

		//Option d'ajout
		$this->chk_addoption = '';
		$addoption = getArgument('addoption');
		if ($addoption == "1") $this->chk_addoption = " checked";

		$me_id = getArgument('me_id');

		$this->di_name = (strlen($usertable) > 8) ? substr($usertable, 0, 8) : $usertable;
		$this->di_short = $usertable;
		$this->di_long = "Liste des " . $usertable;

	}
}