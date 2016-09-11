<?php
namespace Phink\MVC;

use Phink\MVC\TCustomView;
use Phink\Core\TRegistry;

class TPartialView extends TCustomView 
{

    public function __construct(\Phink\Core\TObject $father, \Phink\Core\TObject $parent)
    {
        $this->parentView = $father;
        $this->className = $parent->getType();
        parent::__construct($parent);
//        $this->depth += $this->view->getDepth();
        //\Phink\Log\TLog::debug('FATHER TYPE <> PARENT TYPE : ' . $father->getType() . ' <> ' . $this->className, __FILE__, __LINE__);
        $this->request = $parent->getRequest();
        $this->response = $parent->getResponse();        

//        if($this->depth > 8) {
//            throw new \Exception("Partial views are emboxed beyond level 8. Please check that you did'nt embox a partial view in itself. This may turn in an infinite loop.");
//        }
    }

    public function setViewName()
    {
        $this->viewName = lcfirst($this->className);
    }

    public function setNames()
    {
        
        $this->controllerFileName = 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $this->viewName . DIRECTORY_SEPARATOR . $this->viewName . CLASS_EXTENSION;
        $this->viewFileName = 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $this->viewName . DIRECTORY_SEPARATOR . $this->viewName . PREHTML_EXTENSION;
        
        if(!file_exists($this->viewFileName)) {

            if($info = TRegistry::classInfo($this->className))
{
                $this->viewName = \Phink\TAutoloader::classNameToFilename($this->className);
                if($info->hasTemplate) {
                    $this->viewFileName = ROOT_PATH . $info->path . $this->viewName . PREHTML_EXTENSION;
                } else {
                    $this->viewFileName = '';
                }
                $this->controllerFileName = ROOT_PATH . $info->path . $this->viewName . CLASS_EXTENSION;
                $this->className = $info->namespace . '\\' . $this->className;
            }

            $this->getCacheFileName();
        }
                
    }

}
