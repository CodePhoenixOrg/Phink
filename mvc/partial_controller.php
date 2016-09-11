<?php
namespace Phink\MVC;

//require_once 'custom_controller.php';
//require_once 'partial_view.php';

use Phink\MVC\TPartialView;
use Phink\MVC\TCustomController;

class TPartialController extends TCustomController 
{
    
    public function __construct(\Phink\Core\TObject $parent)
    {
        //$this->setParent($parent->getView())
        parent::__construct($parent);
        
        $this->className = $this->getType();
        $this->viewName = lcfirst($this->className);
         //\Phink\Log\TLog::debug('PARTIAL CONTROLLER TYPE : ' . print_r($this->className, true));
       
        $include = \Phink\TAutoloader::includeModelByName($this->viewName);
        $modelClass = $include['type'];
//        //\Phink\Log\TLog::debug('MODEL OBJECT : ' . print_r($modelClass, true));
        $this->model = new $modelClass();        
        $this->view = new TPartialView($parent, $this); 
                
    }   
    
    public function render()
    {
        $this->init();
        $this->parse();
        $this->beforeBinding();
        $this->renderCreations();
        $this->renderDeclarations();
        $this->renderView();
        if(!$this->isRendered) {
            $this->renderHtml();
            $this->renderedHtml();
        }
        $this->unload();    
    }    
    
    public function __destruct()
    {
        unset($this->model);
        unset($this->view);
    }
    
}
