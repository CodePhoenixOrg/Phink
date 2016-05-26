<?php
namespace Phink\MVC;

//require_once 'phink/core/object.php';

abstract class TCustomController extends \Phink\Web\UI\TCustomControl
{
    use \Phink\Web\TWebObject;

    protected $innerHtml = '';
    protected $creations = '';
    protected $declarations = '';
    protected $beforeBinding = '';
    protected $afterBinding = '';
    protected $viewHtml = '';
    protected $model = NULL;
    
    protected $view = NULL;
    
    public function __construct(\Phink\Core\TObject $parent)
    {
        parent::__construct($parent);
        
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
        $isAlreadyParsed = $this->view->parse();

        if(!$isAlreadyParsed) {
//            $this->innerPhp = $this->view->getPreHtml();
//        } else {
            $this->creations = $this->view->getCreations();
            $this->declarations = $this->view->getAdditions();
            $this->viewHtml = $this->view->getViewHtml();
 
        }
    }

    public function renderCreations()
    {
      if(!empty($this->creations)) {
        include_once "data://text/plain;base64," . base64_encode('<?php' . $this->creations . '?>');
      }
    }

    public function renderDeclarations()
    {
      if(!empty($this->declarations)) {
        include_once "data://text/plain;base64," . base64_encode('<?php' . $this->declarations . '?>');
      }
    }

    public function renderAfterBinding()
    {
      if(!empty($this->afterBinding)) {
        include_once "data://text/plain;base64," . base64_encode('<?php' . $this->afterBinding . '?>');
      }
    }

    public function renderView()
    {
        include_once "data://text/plain;base64," . base64_encode($this->viewHtml);
    }

    public function renderedHtml()
    {
        include_once "data://text/plain;base64," . base64_encode($this->innerHtml);
    }
    
    public function renderedPhp()
    {
        $this->renderCreations();
        $this->renderDeclarations();
        //$this->beforeBinding();
        //$this->renderAfterBinding();
        $this->renderView();
    }

    public function perform() {}
    
    public function getViewHtml()
    {
        ob_start();
        
        $this->parse();
        $this->renderedPhp();
        $html = ob_get_clean();

        $this->response->setData('view', $html);
    }
    
    

    
}