<?php
namespace Phoenix\MVC;

//require_once 'phoenix/core/object.php';

abstract class TCustomController extends \Phoenix\Web\UI\TCustomControl
{
    use \Phoenix\Web\TWebObject;

    protected $innerHtml = '';
    protected $innerPhp = '';
    protected $model = NULL;
    protected $view = NULL;
    
    public function __construct(\Phoenix\Core\TObject $parent)
    {
        $this->setParent($parent);
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
            $creations = $this->view->getCreations();
            $additions = $this->view->getAdditions();
            $viewHtml = $this->view->getViewHtml();
            $this->innerPhp ='<?php ' . $creations . $additions . '?>' . $viewHtml;
        }
    }

    public function renderedPhp()
    {
        include_once "data://text/plain," . urlencode($this->innerPhp);
    }

    public function renderedHtml()
    {
        include_once "data://text/plain," . urlencode($this->innerHtml);
    }
    
    public function perform() {}
    
    public function getViewHtml()
    {
        ob_start();
        $this->parse();
        $this->renderedPhp();
        $html = ob_get_clean();
        $this->unload();

        $this->response->setData('view', $html);
    }
    
    

    
}
