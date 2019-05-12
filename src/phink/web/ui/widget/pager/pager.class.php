<?php
/*
 * Copyright (C) 2016 David Blanchard
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
 
 namespace Phink\Web\UI\Widget\Pager;

class TPager extends \Phink\MVC\TPartialController
{

    protected $pageCount;
    protected $caption;
    protected $currentPage;
    protected $pageNum;
    protected $statement;
    protected $onclick;
    protected $script;
    protected $pagerJS;
    protected $for;

    public function setStatement($value)
    {
        $this->statement = $value;
    }

    public function setCaption($value)
    {
        $this->caption = $value;
    }

    public function setPageCount($value)
    {
        $this->pageCount = $value;
    }
    
    public function setCurrentPage($value)
    {
        $this->currentPage = $value;
    }
    
    public function setPageNum($value)
    {
        $this->pageNum = $value;
    }
    
    public function setOnclick($value)
    {
        $this->onclick = $value;
    }
    
    public function setFor($value)
    {
        $this->for = $value;
    }

    public function getCacheFilename() {
        return SITE_ROOT . REL_RUNTIME_DIR . str_replace(DIRECTORY_SEPARATOR, '_', $this->path . $this->for . 'pager' . CLASS_EXTENSION);
    }

    public function init()
    {
        $forControl = $this->parent->getChildById($this->for);
    
        $this->pageNum = (int) (!$this->pageNum) ? 1 : $this->pageNum;
        
        $this->pageCount = ($forControl) ? $forControl->getRowCount(): $this->pageNum;
    
        $this->path = ROOT_PATH . \Phink\Core\TRegistry::classPath('TPager');
        $this->pagerJS = file_get_contents($this->path . 'pager.jhtml', FILE_USE_INCLUDE_PATH);
        $this->pagerJS = str_replace('<% for %>', $this->for, $this->pagerJS);
        $this->pagerJS = str_replace('<% pageCount %>', $this->pageCount, $this->pagerJS);
        $this->pagerJS = str_replace('<% pageNum %>', $this->pageNum, $this->pagerJS);
        $this->pagerJS = str_replace('<% id %>', $this->id, $this->pagerJS);
        $this->pagerJS = str_replace('<% onclick %>', $this->onclick, $this->pagerJS);
        
        $this->script = REL_RUNTIME_JS_DIR . str_replace(DIRECTORY_SEPARATOR, '_', $this->path . $this->for . 'pager.js');

        file_put_contents($this->script, $this->pagerJS);
        $this->response->addScript($this->script);
    }
}
