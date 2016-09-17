<?php
namespace Phink\Web\UI;

use Phink\Core\TObject;

class TControl extends TCustomControl
{

    protected $model = NULL;
    protected $innerHtml = '';
    protected $isDreclared = false;

    public function __construct(TObject $parent)
    {
        
        $this->setParent($parent);
        
        $this->setViewName();
        $this->setNamespace();
        $this->setNames();
        
        $this->className = $this->getType();
        $this->viewName = lcfirst($this->className);
        
        $include = \Phink\TAutoloader::includeModelByName($this->viewName);
        $modelClass = $include['type'];
        //\Phink\Log\TLog::debug('TCONTROL MODEL OBJECT : ' . print_r($modelClass, true));
        $this->model = new $modelClass();        

        $this->request = $parent->getRequest();
        $this->response = $parent->getResponse();        
    }

    public function getModel()
    {
        return $this->model;
    }
       
    public function getInnerHtml()
    {
        return $this->innerHtml;
    }

    public function createObjects() {}
    
    public function declareObjects() {}

    public function afterBinding() {}
    
    public function displayHtml() {}
    
    public function getViewHtml()
    {
        ob_start();
        if(!$this->isDreclared) {
            //$this->createObjects();
            $this->declareObjects();
//            $this->partialLoad();
        }
        $this->displayHtml();
        $html = ob_get_clean();
        $this->unload();

/*        
        $cachedJsController = RUNTIME_DIR . \Phink\TAutoloader::cacheJsFilenameFromView($this->viewName);
        \Phink\Log\TLog::debug(__METHOD__ . '::1::' . $cachedJsController);
        if(file_exists($cachedJsController)) {
            $jsCode = file_get_contents($cachedJsController);
            $html .= CR_LF . "?>" .CR_LF . $jsCode . CR_LF;
            \Phink\Log\TLog::debug(__METHOD__ . '::2::' . $cachedJsController);
            
            $this->response->addScript($cachedJsController);
        }
*/        
        \Phink\Log\TLog::debug(__METHOD__ . '::1::' . $this->getJsControllerFileName());
        if(file_exists($this->getJsControllerFileName())) {
            \Phink\Log\TLog::debug(__METHOD__ . '::2::' . $this->getJsControllerFileName());
            $this->response->addScript($this->getJsControllerFileName());
        }
        $this->response->setData('view', $html);

    }   
    
    public function render()
    {
        $this->createObjects();
        $this->init();
        $this->beforeBinding();
        $this->declareObjects();
//        $this->afterBinding();
        $this->isDreclared = true;
        $this->displayHtml();
        $this->renderHtml();
        $this->unload();
    }
    
    public function perform()
    {
        $this->createObjects();
        $this->init();
        if($this->request->isAJAX()) {
            $actionName = $this->actionName;
            
            $params = $this->validate($actionName);
            $this->invoke($actionName, $params);

            $this->beforeBinding();
            $this->declareObjects();
            
            if($this->request->isPartialView()
            || ($this->request->isView() && $actionName !== 'getViewHtml')) {
                $this->getViewHtml();
            }

            $this->response->sendData();
        } else {
            $this->beforeBinding();
            $this->declareObjects();
            $this->load();
            $this->displayHtml();
            $this->unload();
        }        
    }
    
    public function __destruct()
    {
        unset($this->model);
    }

}
