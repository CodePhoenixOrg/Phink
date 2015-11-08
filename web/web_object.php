<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Phoenix\Web;

 /**
 * Description of TObject
 *
 * @author david
 */

trait TWebObject {
    //put your code here
    private static $_currentDirectory;
    private static $_currentFilePath;
    private static $_currentClassName;
    private static $_currentNamespace;
    private static $_sqlConfigurationFileName;
    private static $_pageNumber;
    private static $_pageCount;
    protected $redis = null;
    protected $response = null;
    protected $request = null;
    protected $modelFileName = '';
    protected $viewFileName = '';
    protected $controllerFileName = '';
    protected $jsControllerFileName = '';
    protected $cacheFileName = '';
    protected $preHtmlName = '';
    protected $viewName = '';
    protected $actionName = '';
    protected $className = '';
    protected $namespace = '';
    protected $code = '';
    
    
//    public function __construct(TObject $parent)
//    {
//        $this->request = $parent->getRequest();
//        $this->response = $parent->getResponse();        
//    }
//

    public static function currentDirectory($value = null)
    {
        if(isset($value)) {
            self::$_currentDirectory = $value;
        }
        else {
            return self::$_currentDirectory;
        }
    }

    public static function currentFilePath($value = null)
    {
        if(isset($value)) {
            self::$_currentFilePath = $value;
        }
        else {
            return self::$_currentFilePath;
        }
    }

    public static function currentNamespace($value = null)
    {
        if(isset($value)) {
            self::$_currentNamespace = $value;
        }
        else {
            return self::$_currentNamespace;
        }
    }

    public static function currentClassName($value = null)
    {
        if(isset($value)) {
            self::$_currentClassName = $value;
        }
        else {
            return self::$_currentClassName;
        }
    }

    public static function sqlConfigurationFileName($value = null)
    {
        if(isset($value)) {
            self::$_sqlConfigurationFileName = $value;
        }
        else {
            return self::$_sqlConfigurationFileName;
        }
    }

    public static function pageNumber($value = null)
    {
        if(isset($value)) {
            self::$_pageNumber = $value;
        }
        else {
            return self::$_pageNumber;
        }
    }

    public static function pageCount($value = null)
    {
        if(isset($value)) {
            self::$_pageCount = $value;
        }
        else {
            return self::$_pageCount;
        }
    }

    public function pageCountByDefault($default)
    {
        self::pageCount($this->request->getQueryArguments(PAGE_COUNT));
        if(!self::pageCount()) {
            self::pageCount($default);
        }

        if($default < 1) {
            self::pageCount(PAGE_COUNT_DEFAULT);
        }

        return self::pageCount();
    }

    public static function pagerQuery($pageNumber, $pageCount = NULL)
    {
        $result = '';
        if (isset($pageCount) && isset($pageNumber)) {
            $result = PAGE_COUNT .  '=' .  $pageCount . '&' . PAGE_NUMBER . '=' . $pageNumber;
        }
        else if (!isset($pageCount) && isset($pageNumber)) {
            $result = PAGE_COUNT .  '=' .  self::$_pageCount . '&' . PAGE_NUMBER . '=' . $pageNumber;
        }
        else {
            $result = PAGE_COUNT .  '=' .  self::$_pageCount . '&' . PAGE_NUMBER . '=' . self::$_pageNumber;
        }
        return $result;
    }
    
    public function getClassFileName()
    {
        $parts = pathinfo($this->getFileName());
        return $parts['dirname'] . DIRECTORY_SEPARATOR . $parts['filename'] . CLASS_EXTENSION;
    }

    public function getTemplateName()
    {
        $parts = pathinfo($this->getFileName());
        return $parts['dirname'] . DIRECTORY_SEPARATOR . str_replace('.class', '', $parts['filename']) . PREHTML_EXTENSION; //'.' . $parts['extension'];
    }

//    public function getPatternName()
//    {
//        $parts = pathinfo($this->getFileName());
//        $classPath = \Phoenix\Core\TRegistry::classPath('T' . ucfirst($parts['filename']));
//        return strtolower(ROOT_NAMESPACE) . $classPath . $parts['filename'] . PATTERN_EXTENSION;
//    }

    public function getDocumentFileName()
    {
        $parts = pathinfo($this->getFileName());
        return DOCUMENT_ROOT . DIRECTORY_SEPARATOR . $parts['filename'] . '.' . $parts['extension'];
    }

    public function getPreHtmlDebugName()
    {
        $parts = pathinfo($this->getFileName());
        return DOCUMENT_ROOT . 'tmp' . DIRECTORY_SEPARATOR . 'dbg_' . $parts['filename'] . PREHTML_EXTENSION;
    }
    
    public function getPreHtmlName()
    {
        if($this->preHtmlName == '') {
            $this->preHtmlName = TMP_DIR . DIRECTORY_SEPARATOR . str_replace(DIRECTORY_SEPARATOR, '_', $this->viewFileName);
        }
        return $this->preHtmlName;
    }

    public function getCacheFileName()
    {
        $this->cacheFileName = TMP_DIR . DIRECTORY_SEPARATOR . str_replace(DIRECTORY_SEPARATOR, '_', $this->controllerFileName);
        return $this->cacheFileName;
    }
    
    public function getPhpCode()
    {

        if(!$this->code) {
//        $this->code = $this->redis->mget($this->getCacheFileName());
//        $this->code = $this->code[0];
            if(file_exists($this->getCacheFileName())) {
                $this->code = file_get_contents($this->getCacheFileName());
            }
        }

        return $this->code;
    }
    
    public function preHtmlExists()
    {
        return file_exists($this->getPreHtmlName());
    }
        
    public function getGlobalDesignName()
    {
        $parts = pathinfo($this->getFileName());
        return DOCUMENT_ROOT . 'tmp' . DIRECTORY_SEPARATOR . $parts['filename'] . '.design.php';
    }

    public function getJsonName()
    {
        
        return TMP_DIR . DIRECTORY_SEPARATOR . $this->className . '.json';
    }

    public function getConfigName()
    {
        $parts = pathinfo($this->getFileName());
        return DOCUMENT_ROOT . 'config' . DIRECTORY_SEPARATOR . $parts['filename'] . '.config.' . $parts['extension'];
    }

    public function getXmlName()
    {
        $parts = pathinfo($this->getFileName());
        return DOCUMENT_ROOT . 'tmp' . DIRECTORY_SEPARATOR . $parts['filename'] . '.xml';
    }
    
    public function setRedis(array $params)
    {
        $this->redis = $params;
    }

    public function getRedis()
    {
        return $this->redis;
    }
    
    public function getRequest()
    {
        return $this->request;
    }
    
    public function getResponse()
    {
        return $this->response;
    }
    
    public function getClassName()
    {
        return $this->className;
    }
    
    public function getActionName()
    {
        return $this->actionName;
    }
    
    public function getFileNamespace()
    {
        return $this->namespace;
    }
    
    public function getRawPhpName()
    {
        return $this->cacheFileName;
    }
    
    public function getModelFileName()
    {
        return $this->modelFileName;
    }

    public function getViewFileName()
    {
        return $this->viewFileName;
    }

    public function getControllerFileName()
    {
        return $this->controllerFileName;
    }    

    public function getJsControllerFileName()
    {
        return $this->jsControllerFileName;
    }    

    public function getViewName()
    {
        return $this->viewName;
    }
    
    public function setViewName()
    {
        $this->viewName = array_pop(explode('/', REQUEST_URI));
        $this->viewName = array_shift(explode('.',$this->viewName));

        $this->viewName = ($this->viewName == '') ? MAIN_VIEW : $this->viewName;
        $this->className = ucfirst($this->viewName);

//        \Phoenix\Log\TLog::debug('VIEW NAME : '  . $this->viewName, __FILE__, __LINE__);
        
    }
    
    public function setNamespace()
    {
        $this->namespace = $this->getFileNamespace();
        
        if(!isset($this->namespace)) {
            $this->namespace = UI\TCustomControl::getDefaultNamespace();
        }

    }
    
    public function setFilenames()
    {
    
        $this->actionName = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';

        $this->modelFileName = 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . $this->viewName . CLASS_EXTENSION;
        
        $this->viewFileName = 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $this->viewName . DIRECTORY_SEPARATOR . $this->viewName . PREHTML_EXTENSION;
        
        $this->controllerFileName = 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $this->viewName . DIRECTORY_SEPARATOR . $this->viewName . CLASS_EXTENSION;

        $this->jsControllerFileName = 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $this->viewName . DIRECTORY_SEPARATOR . $this->viewName . JS_EXTENSION;
        
        $this->getCacheFileName();

    }
    
}
