<?php

namespace Phink\Apps\Admin;

use Phink\MVC\TController;
use Phink\Web\UI\TScriptMaker;
use Puzzle\Menus;
use Puzzle\Design;
use Phink\Registry\TRegistry;
use Puzzle\Source as PHPSource;

class Source extends TController
{
	protected $source;
    protected $appTitle;
    protected $page_colors;
    protected $grid_colors;
    protected $panel_colors;
    protected $file;
    protected $lang;
	
	public function load() : void 
	{
        $this->page_colors = (object)TRegistry::ini('page_colors');
        $this->grid_colors = (object)TRegistry::ini('grid_colors');
        $this->panel_colors = (object)TRegistry::ini('panel_colors');
        $this->appName = $this->getApplication()->getName();
		$this->appTitle = $this->getApplication()->getTitle() . " Admin";
		
		$phpsrc = new PHPSource;

		$this->file = getArgument("file");

		$script = file_get_contents($this->file);
		$this->source = $phpsrc->highlightPhp($script, true);

	}
}