<?php

namespace Phink\Apps\Admin;

use Phink\MVC\TPartialController;
use Phink\Registry\TRegistry;
use Puzzle\Menus;

class TMakeMain extends TPartialController
{
	// tools
	protected $menus, $conf, $lang, $db_prefix;

	// view fields
	protected $tab_ctrl_name, $rad_choice, $choice, $tab_ides, $on_click, $tab_start, $page;
	
	public function beforeBinding(): void
    {		
        $this->lang = TRegistry::ini('application', 'lang');
        $this->db_prefix = TRegistry::ini('data', 'db_prefix');
		$this->conf = TRegistry::ini('data', 'conf');
		$this->menus = new Menus($this->lang, $this->db_prefix);
		$this->tab_ctrl_name="myTab";
		//Options de départ
		$this->rad_choice = ['', '', ''];
		$this->choice = getArgument('choice', 0);
		$this->rad_choice[$this->choice]=" checked"; 
		$this->tab_ides = $this->menus->getTabIdes($this->conf);
		array_shift($this->tab_ides);
		$this->on_click="var index=get_radio_value(\"myTabForm\", \"choice\");";
		$this->on_click.=jsArray("myTabCaptions", $this->tab_ides);
		$this->on_click.="location.href=\"admin?di=\"+myTabCaptions[index]+\"&lg=$this->lang\";";
	}
}