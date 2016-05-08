<?php
namespace Phoenix\MVC;

//require_once 'custom_controller.php';
//require_once 'partial_view.php';

use Phoenix\MVC\TPartialView;
use Phoenix\MVC\TCustomController;

class TPartialController extends TCustomController 
{
    
    public function __construct(\Phoenix\Core\TObject $parent)
    {
        //$this->setParent($parent->getView())
        parent::__construct($parent);
        
        $this->className = $this->getType();
        $this->viewName = lcfirst($this->className);
         //\Phoenix\Log\TLog::debug('PARTIAL CONTROLLER TYPE : ' . print_r($this->className, true));
       
        $include = \Phoenix\TAutoloader::includeModelByName($this->viewName);
        $modelClass = $include['type'];
//        //\Phoenix\Log\TLog::debug('MODEL OBJECT : ' . print_r($modelClass, true));
        $this->model = new $modelClass();        
        $this->view = new TPartialView($parent, $this); 
                
    }

    public function perform()
    {
        $this->init();
        $actionName = $this->actionName;
        
        \Phoenix\TAutoloader::validateMethod($this, $actionName);
            
        $this->$actionName();
        $this->getViewHtml();
        $this->unload();
        $this->response->sendData();
    }    
    
    public function render()
    {
        $this->init();
        $this->parse();
        $this->renderedPhp();
        if(!$this->isRendered) {
            $this->renderHtml();
            $this->renderedHtml();
        }
        $this->unload();    
    }    
    
}
