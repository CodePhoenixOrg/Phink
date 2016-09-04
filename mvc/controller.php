<?php
namespace Phink\MVC;

class TController extends TCustomController
{

    public function __construct(TView $view, TModel $model)
    {
        parent::__construct($view);
        
        $this->view = $view;
        $this->model = $model;
        
    }    

    public function perform()
    {
        $this->init();
        if($this->request->isAJAX()) {
            $actionName = $this->actionName;

            $this->parse();
            $this->renderCreations();
            
            $params = $this->validate($actionName);
            $this->invoke($actionName, $params);

            $this->beforeBinding();
            $this->renderDeclarations();
            
            if($this->request->isPartialView()
            || ($this->request->isView() && $this->actionName !== 'getViewHtml')) {
                $this->getViewHtml();
            }
            $this->response->sendData();
        } else {
            $this->load();
            $this->parse();
            $this->beforeBinding();
            $this->renderCreations();
            $this->renderDeclarations();
            $this->renderView();
            $this->unload();
        }        
    }
    
    public function __destruct()
    {
        unset($this->model);
        unset($this->view);
    }

}
