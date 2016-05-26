<?php
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


    public function init()
    {
        $forControl = $this->parent->getChildById($this->for);
    
        $this->pageNum = (int) (!$this->pageNum) ? 1 : $this->pageNum;
        
        $this->pageCount = ($forControl) ? $forControl->getRowCount(): $this->pageNum;
    
        $path = ROOT_PATH . \Phink\Core\TRegistry::classPath('TPager');
        $this->pagerJS = file_get_contents($path . 'pager.js', FILE_USE_INCLUDE_PATH);
        $this->pagerJS = str_replace('<% pageCount %>', $this->pageCount, $this->pagerJS);
        $this->pagerJS = str_replace('<% pageNum %>', $this->pageNum, $this->pagerJS);
        $this->pagerJS = str_replace('<% id %>', $this->id, $this->pagerJS);
        $this->pagerJS = str_replace('<% onclick %>', $this->onclick, $this->pagerJS);
        
        $this->script = TMP_DIR . DIRECTORY_SEPARATOR . str_replace(DIRECTORY_SEPARATOR, '_', $path . 'pager.js');

        file_put_contents($this->script, $this->pagerJS);
        $this->response->addScript($this->script);
    }
}
