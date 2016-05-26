<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Phink\MVC;

//require_once 'phink/core/object.php';
//require_once 'phink/core/response.php';
//require_once 'custom_view.php';
//require_once 'controller.php';
//require_once 'phink/utils/file_utils.php';

use Phink\Web\TWebApplication;
use Phink\Web\TRequest;
use Phink\Web\TResponse;
use Phink\MVC\TCustomView;
use Phink\Utils\TFileUtils;
use Phink\Tuth\TAuthentication;

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
