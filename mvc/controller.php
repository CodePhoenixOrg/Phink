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

            $this->validate($actionName);
            $this->invoke($actionName);

            //$this->$actionName();
            if($this->request->isPartialView()) {
                $this->getViewHtml();
            }
            $this->response->sendData();
        } else {
            $this->load();
            $this->parse();
            $this->renderedPhp();
            $this->unload();
        }        
    }
    
    public function __destruct()
    {
        unset($this->model);
        unset($this->view);
    }

}
