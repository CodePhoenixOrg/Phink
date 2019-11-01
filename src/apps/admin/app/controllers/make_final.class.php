<?php
namespace Phink\Apps\Admin;

use Phink\MVC\TPartialController;
use Phink\Registry\TRegistry;
use Phink\Data\Client\PDO\TPdoConnection;
use Phink\Data\TAnalyzer;
use Phink\Log\TLog;
use Puzzle\Menus;

class TMakeFinal extends TPartialController
{
	// tools
	protected $page, $menus, $conf, $lang, $db_prefix;

	// view fields
	protected $userdb, $usertable, $menu, $filter, $addoption, $me_level, $bl_id, $pa_filename,
		$extension, $basedir, $save, $autogen, $catalog, $query, $di_long, $di_short, $di_name, 
		$lg, $wwwroot, $rel_page_filename, $script_exists_tostring, $props, $mindex, $me_id, 
		$pa_id, $sstatus, $YES = 'Oui', $NO = 'Non';

	public function beforeBinding(): void
    {
        $this->lang = TRegistry::ini('application', 'lang');
        $this->db_prefix = TRegistry::ini('data', 'db_prefix');
		$this->conf = TRegistry::ini('data', 'conf');
		$this->menus = new Menus($this->lang, $this->db_prefix);
		
		$this->userdb = getArgument("userdb");
		$this->usertable = getArgument("usertable");
		$this->dbgrid = getArgument("dbgrid");
		$this->menu = getArgument("menu");
		$this->filter = getArgument("filter");
		$this->addoption = getArgument("addoption");
		$this->me_level = getArgument("me_level", "1");
		$this->bl_id = getArgument("bl_id");
		$this->pa_filename = getArgument("pa_filename");
		$this->extension = getArgument("extension");
		$this->basedir = getArgument("basedir");
		$this->save = getArgument("save");
		$this->autogen = getArgument("autogen");
		$this->catalog = getArgument("catalog", 0);
		$this->query = getArgument("query");
		$this->di_long = getArgument("di_long");
		$this->di_short = getArgument("di_short");
		$this->di_name = getArgument("di_name");
		$this->lg = getArgument("lg", "fr");

		$this->indexfield = getArgument("indexfield");
		$this->secondfield = getArgument("secondfield");
		$this->pa_id = getArgument("pa_id");
		$this->me_id = getArgument("me_id");

		$cs = TPdoConnection::opener($this->conf);
		$tmp_filename = 'tmp_' . $this->pa_filename;
		$this->wwwroot = getWwwRoot();

		$this->rel_page_filename = $this->pa_filename . $this->extension;

		$this->basedir .= "/";
		$this->basedir = str_replace('./', "/", $this->basedir);
		$this->basedir = str_replace('//', "/", $this->basedir);

		$root_code_filename = $this->wwwroot . $this->basedir . $this->pa_filename . '_code' . $this->extension;
		$root_page_filename = $this->wwwroot . $this->basedir . $this->pa_filename . $this->extension;

		$script_exists = file_exists($this->rel_page_filename);
		$this->script_exists_tostring = $script_exists ? $this->YES : $this->NO;
		$http_root = getHttpRoot();


		if ($this->dbgrid == "0") {
			$this->props = "Grille";
		} else {
			$this->props = "Grille + Fiche";
		}
		if ($this->filter == "1") {
			$this->props .= " + Filtre";
		}
		if ($this->addoption == "1") {
			$this->props .= " + Bouton Ajouter";
		}

		if ($this->me_id == "") {
			$this->mindex = "Auto-incrémenté";
		} else {
			$this->mindex = $this->me_id;
		}

		if ($this->save == $this->YES) {

			copy('tmp_code.php', $root_code_filename);
			copy('tmp_page.php', $root_page_filename);
			self::getLogger()->debug('FILE_PAGE:' . $root_code_filename, __FILE__ , __LINE__);
			self::getLogger()->debug('FILE_CODE:' . $root_page_filename, __FILE__ , __LINE__);

			$this->sstatus = "Page enregistrée";
		} elseif ($this->save == $this->NO) {
			$this->sstatus = "Page non-enregistrée";
			$this->menus->deleteMenu($this->conf, $this->di_name);

			if (file_exists($root_code_filename)) {
				unlink($root_code_filename);
			}
			if (file_exists($root_page_filename)) {
				unlink($root_page_filename);
			}
		}
		unlink('tmp_code.php');
		unlink('tmp_page.php');

	}
	
}
