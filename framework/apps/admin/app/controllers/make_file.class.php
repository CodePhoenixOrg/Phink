<?php
namespace Phink\Apps\Admin;

use Phink\MVC\TPartialController;
use Phink\Registry\TRegistry;
use Phink\Web\UI\TMvcScriptMaker;
use Puzzle\Menus;

class TMakeFile extends TPartialController
{
	// tools
	protected $menus, $workconf, $workdb, $userconf, $userdb, $lang, $db_prefix, $scriptMaker;

	// view fields
	protected $usertable, $dbgrid, $menu, $filter, $addoption, $me_level, $bl_id,
		$pa_filename, $extension, $basedir, $save, $autogen, $catalog, $query, $di_long,
		$di_short, $di_name, $lg, $me_id, $pa_id, $message,
		$YES = 'Oui', $NO = 'Non';

	public function beforeBinding(): void
    {
        $this->lang = TRegistry::ini('application', 'lang');
        $this->db_prefix = TRegistry::ini('data', 'db_prefix');
		$this->conf = TRegistry::ini('data', 'conf');
		$this->menus = new Menus($this->lang, $this->db_prefix);
        $this->scriptMaker = new TMvcScriptMaker;

		$data = $this->getRequest()->getArgument("data");

		$this->workdb = getArgument("workdb");
		$this->userconf = getArgument("userconf");
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
	
		$A_data = array_map(function($defs) {
			return base64_decode($defs);
		}, $data);

		$this->message = "<br>";

		$rel_page_filename = $this->pa_filename . CLASS_EXTENSION;

		$this->basedir .= "/";
		$this->basedir = str_replace('./', "/", $this->basedir);
		$this->basedir = str_replace('//', "/", $this->basedir);


		$script_exists = file_exists($rel_page_filename);

		if ($this->save === "") {

			// $formname = "fiche_$this->usertable";

			list($this->me_id, $this->pa_id) = $this->menus->getMenuAndPage($this->conf, $rel_page_filename);

			$this->message .= "Catalog file name: $rel_page_filename<br>";

			if ($this->me_id !== 0) {
				$this->message .= "<p style='color:red'>Le script $rel_page_filename existe déjà sous l'id de menu $this->me_id.</p>";
			}
			if ($this->pa_id !== 0) {
				$this->message .= "<p style='color:red'>Le script $rel_page_filename existe déjà sous l'id de page $this->pa_id.</p>";
			}

			if (($this->me_id == 0 || $this->pa_id == 0) && $this->autogen == 1) {
				list($this->me_id, $this->pa_id) = $this->menus->addMenuAndPage(
					$this->conf,
					$this->di_name,
					$this->me_level,
					'page',
					$rel_page_filename,
					$this->di_short,
					$this->di_long
				);

				$this->message .= "<p style='color:red'>Le script $rel_page_filename a été ajouté au triplet dictionnaire-page-menu sous l'id de page $this->pa_id et l'id de menu $this->me_id.</p>";
			}

			//echo "$cur_pa_filename<br>";
			$this->message .= "<p>Voulez-vous conserver le script ?</p>\n";
			if ($script_exists) {
				$this->message .= "<p style='color:red'>Attention ! Un fichier portant ce nom existe déjà.<br>" .
					"Voulez-vous écraser le script actuel sachant que toutes les modifications effectuées seront perdues ?</p>\n";
			}

			$script = $this->scriptMaker->makeCode($this->userconf, $this->usertable, $this->pa_id, $A_data);
			file_put_contents('tmp_code.php', $script);

			$script = $this->scriptMaker->makePage($this->userdb, $this->usertable, $this->pa_filename, $this->pa_id, $A_data);
			file_put_contents('tmp_page.php', $script);
		

		}

	}
}
