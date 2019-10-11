<?php
namespace Phink\Apps\Admin;

use Phink\MVC\TPartialController;
use Phink\Registry\TRegistry;
use Phink\Data\Client\PDO\TPdoConnection;
use Phink\Data\TAnalyzer;
use Phink\Web\UI\TScriptMaker;
use Phink\Log\TLog;
use Puzzle\Menus;
use Puzzle\Controls;
use Puzzle\Data\Controls as DbControls;

class TMakeFile extends TPartialController
{
	// tools
	protected $menus, $conf, $lang, $db_prefix, $scriptMaker;

	// view fields
	protected $userdb, $usertable, $dbgrid, $menu, $filter, $addoption, $me_level, $bl_id,
		$pa_filename, $extension, $basedir, $save, $autogen, $catalog, $query, $di_long,
		$di_short, $di_name, $lg, $me_id, $pa_id, $message, $indexfield, $secondfield,
		$YES = 'Oui', $NO = 'Non';

	public function beforeBinding(): void
    {
        $this->lang = TRegistry::ini('application', 'lang');
        $this->db_prefix = TRegistry::ini('data', 'db_prefix');
		$this->conf = TRegistry::ini('data', 'conf');
		$this->menus = new Menus($this->lang, $this->db_prefix);
        $this->scriptMaker = new TScriptMaker;

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
		$cs = TPdoConnection::opener($this->conf);
		// $this->cs = connection(CONNECT, $userdb) or die("UserDb='$userdb'<br>");
		$tmp_filename = 'tmp_' . $this->pa_filename;
		$wwwroot = getWwwRoot();

		$analyzer = new TAnalyzer;
		$references = $analyzer->searchReferences($this->userdb, $this->usertable, $cs);
		$A_fieldDefs = $references["field_defs"];

		$this->message = "<br>";

		$rel_page_filename = $this->pa_filename . $this->extension;

		$this->basedir .= "/";
		$this->basedir = str_replace('./', "/", $this->basedir);
		$this->basedir = str_replace('//', "/", $this->basedir);

		$root_code_filename = $wwwroot . $this->basedir . $this->pa_filename . '_code' . $this->extension;
		$root_page_filename = $wwwroot . $this->basedir . $this->pa_filename . $this->extension;

		$script_exists = file_exists($rel_page_filename);
		$script_exists_tostring = $script_exists ? $this->YES : $this->NO;
		$http_root = getHttpRoot();

		if ($this->save === "") {

			$formname = "fiche_$this->usertable";
			$sql = "show fields from $this->usertable;";

			$L_sqlFields = "";
			$A_sqlFields = [];

			$stmt = $cs->query($sql);
			while ($rows = $stmt->fetch()) {
				$L_sqlFields .= $rows[0] . ",";
			}

			$L_sqlFields = substr($L_sqlFields, 0, strlen($L_sqlFields) - 1);
			$A_sqlFields = explode(",", $L_sqlFields);
			$this->ndexfield = $A_sqlFields[0];
			$this->secondfield = $A_sqlFields[1];

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

				echo "<p style='color:red'>Le script $rel_page_filename a été ajouté au triplet dictionnaire-page-menu sous l'id de page $this->pa_id et l'id de menu $this->me_id.</p>";
			}

			//echo "$cur_pa_filename<br>";
			$this->message .= "<p>Voulez-vous conserver le script ?</p>\n";
			if ($script_exists) {
				$message = "<p style='color:red'>Attention ! Un fichier portant ce nom existe déjà.<br>" .
					"Voulez-vous écraser le script actuel sachant que toutes les modifications effectuées seront perdues ?</p>\n";
			}

			$script = $this->scriptMaker->makeCode($this->conf, $this->usertable, $stmt, $this->pa_id, $this->indexfield, $this->secondfield, $A_fieldDefs, $cs, false);
			file_put_contents('tmp_code.php', $script);

			$script = $this->scriptMaker->makePage($this->userdb, $this->usertable, $this->pa_filename, $this->pa_id, $this->indexfield, $this->secondfield, $A_sqlFields, $cs, false);
			file_put_contents('tmp_admin', $script);
		

		}

	}
}
