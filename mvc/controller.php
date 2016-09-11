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

    
    public function __destruct()
    {
        unset($this->model);
        unset($this->view);
    }

}
