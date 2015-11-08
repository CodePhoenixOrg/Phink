<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Phoenix\MVC;

//require_once 'phoenix/core/object.php';
//require_once 'phoenix/core/response.php';
//require_once 'custom_view.php';
//require_once 'controller.php';
//require_once 'phoenix/utils/file_utils.php';

use Phoenix\Web\TWebApplication;
use Phoenix\Web\TRequest;
use Phoenix\Web\TResponse;
use Phoenix\MVC\TCustomView;
use Phoenix\Utils\TFileUtils;
use Phoenix\Tuth\TAuthentication;

/**
 * Description of view
 *
 * @author david
 */
class TView extends TCustomView
{
    //put your code here
    
    public function __construct(TWebApplication $parent)
    {

        parent::__construct($parent);
        
        $this->request = $parent->getRequest();
        $this->response = $parent->getResponse();
        
    }

}
