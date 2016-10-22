<?php
namespace Phink\MVC;

abstract class TCustomController extends \Phink\Web\UI\TCustomControl
{

    protected $innerHtml = '';
    protected $creations = '';
    protected $declarations = '';
    protected $beforeBinding = '';
    protected $afterBinding = '';
    protected $viewHtml = '';
    protected $model = null;
    protected $view = null;
    private $_type = '';
    
    public function __construct(\Phink\Core\TObject $parent)
    {

        parent::__construct($parent);
        
        $this->authentication = $parent->getAuthentication();
        $this->request = $parent->getRequest();
        $this->response = $parent->getResponse();        
        
        $this->setViewName();
        $this->setNamespace();
        $this->setNames();
    }

    public function getInnerHtml()
    {
        return $this->innerHtml;
    }
    
    public function clearInnerHtml()
    {
        $this->innerHtml = '';
    }

    public function getView()
    {
        return $this->view;
    }
    
    public function getModel()
    {
        return $this->model;
    }
       
    public function parse()
    {
        $isAlreadyParsed = false; //file_exists(strtolower($this->getCacheFileName()));

        if(!$isAlreadyParsed) {
            $this->_type = $this->view->parse();
//            $this->innerPhp = $this->view->getPreHtml();
//        } else {
            $this->creations = $this->view->getCreations();
            $this->declarations = $this->view->getAdditions();
            $this->viewHtml = $this->view->getViewHtml();
 
        }
        
        return $isAlreadyParsed;
    }

    public function renderCreations()
    {
        if(!empty($this->creations)) {
            include "data://text/plain;base64," . base64_encode('<?php' . $this->creations . '?>');
        }
    }

    public function renderDeclarations()
    {
        if(!empty($this->declarations)) {
            include "data://text/plain;base64," . base64_encode('<?php' . $this->declarations . '?>');
        }
    }

    /*
    public function renderAfterBinding()
    {
        if(!empty($this->afterBinding)) {
            include "data://text/plain;base64," . base64_encode('<?php' . $this->afterBinding . '?>');
        }
    }
    */

    public function renderView()
    {
        include "data://text/plain;base64," . base64_encode($this->viewHtml);
    }

    public function renderedHtml()
    {
        include "data://text/plain;base64," . base64_encode($this->innerHtml);
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
            $this->unload();
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
    
    public function getViewHtml()
    {
        ob_start();
        $this->renderView();
        $html = ob_get_clean();

        $this->response->setData('view', $html);
        
        if(file_exists($this->jsControllerFileName)) {
            $this->response->addScript($this->jsControllerFileName);
        }
    }
    
    

    
}