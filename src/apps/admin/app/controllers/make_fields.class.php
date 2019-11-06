<?php
namespace Phink\Apps\Admin;

use Phink\MVC\TPartialController;
use Phink\Registry\TRegistry;
use Phink\Data\Client\PDO\TPdoConnection;
use Phink\Data\TAnalyzer;
use Phink\Log\TLog;
use Puzzle\Menus;
use Puzzle\Controls;
use Puzzle\Data\Controls as DbControls;

class TMakeFields extends TPartialController
{
	// tools
	protected $page, $menus, $workconf, $workdb, $userconf, $userdb, $lang, $db_prefix;

	// view fields
	protected $pa_filename, $datacontrols, $usertable, $dbgrid, $menu, $filter, 
		$addoption, $me_id, $me_level, $bl_id, $di_long, $di_short, $di_name, 
		$autogen, $extension, $basedir, $lg, $options, $defs, $fields, $data;

	public function beforeBinding(): void
    {		
        $this->lang = TRegistry::ini('application', 'lang');
        $this->db_prefix = TRegistry::ini('data', 'db_prefix');
		$this->workconf = TRegistry::ini('data', 'conf');
		$this->menus = new Menus($this->lang, $this->db_prefix);

		$this->workdb = getArgument("workdb");
		$this->userconf = getArgument("userconf");
		$this->userdb = getArgument("userdb");
		$this->usertable = getArgument("usertable");
		$this->dbgrid = getArgument("dbgrid");
		$this->menu = getArgument("menu");
		$this->filter = getArgument("filter");
		$this->addoption = getArgument("addoption");
		$this->me_id = getArgument("me_id");
		$this->me_level = getArgument("me_level");
		$this->bl_id = getArgument("bl_id");
		$this->di_long = getArgument("di_long");
		$this->di_short = getArgument("di_short");
		$this->di_name = getArgument("di_name");
		$this->autogen = getArgument("autogen");
		$this->pa_filename = getArgument("pa_filename");
		$this->extension = getArgument("extension");
		$this->basedir = getArgument("basedir");
		$this->lg = getArgument('lg');


		$usercs = TPdoConnection::opener($this->userconf);

		$tmp_filename = "tmp.php";
		$wwwroot = getWwwRoot();
		$this->datacontrols = new DbControls($this->lang, $this->db_prefix);

		$analyzer = new TAnalyzer;
		$this->data = $analyzer->searchReferences($this->userdb, $this->usertable, $usercs);

		$this->list = "label,select,text,textarea";

	}

}