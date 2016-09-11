<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Phink\MVC;

/**
 * Description of view
 *
 * @author david
 */
class TView extends TCustomView
{
    //put your code here
    
    public function __construct(\Phink\Web\TWebApplication $parent)
    {

        parent::__construct($parent);
        
        $this->request = $parent->getRequest();
        $this->response = $parent->getResponse();
        
    }

}
