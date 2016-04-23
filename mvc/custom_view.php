<?php
namespace Phoenix\MVC;

//require_once 'phoenix/core/object.php';
//require_once 'phoenix/ui/registry.php';
//require_once 'phoenix/xml/xmldocument.php';
//require_once 'phoenix/xml/xmlmatch.php';
//require_once 'phoenix/utils/file_utils.php';
//require_once 'phoenix/utils/string_utils.php';

use Phoenix\Core\TObject;
use Phoenix\Web\TWebObject;
use Phoenix\Xml\TXmlDocument;
use Phoenix\Xml\TXmlMatch;
use Phoenix\Utils\TFileUtils;
use Phoenix\Utils\TStringUtils;
use Phoenix\Core\TRegistry;
use Predis\Client;

abstract class TCustomView extends \Phoenix\Web\UI\TCustomControl
{
    use \Phoenix\Web\TWebObject;
    use \Phoenix\Web\UI\TCodeGenerator {
        writeDeclarations as private;
        writeHTML as private;
    }

    private $_dirty = false;
    
    protected $router = null;
    protected $viewHtml = NULL;
    protected $preHtml = '';
    protected $designs = array();
    protected $design = '';
    protected $creations = '';
    protected $additions = '';
    protected $afterBinding = '';
    protected $modelIsIncluded = false;
    protected $controllerIsIncluded = false;
    protected $pattern = '';
    protected $depth = 0;
    protected $parentView = NULL;
    protected $parentType = NULL;

    public function __construct(TObject $parent)
    {
        $this->setParent($parent);
        //$this->redis = new Client($this->context->getRedis());
        $this->setViewName();
        $this->setNamespace();
        $this->setNames();
    }

    public function isDirty()
    {
        return $this->_dirty;
    }

    public function getDepth()
    {
        return $this->depth;
    }
    public function setDepth($value)
    {
        $this->depth = $value;
    }

//    public function getPattern()
//    {
//        return $this->pattern;
//    }
//
//    public function setPattern($value)
//    {
//        $this->pattern = $value;
//    }
//
//    public function preHtmlExists()
//    {
//        return file_exists($this->getPreHtmlName());
//    }
    
    public function getCreations()
    {
        return $this->creations;
    }

    public function getAdditions()
    {
        return $this->additions;
    }

    public function getAfterBinding()
    {
        return $this->afterBinding;
    }
    
    public function getViewHtml()
    {
        return $this->viewHtml;
    }
    
    public function parse()
    {
        ////\Phoenix\Log\TLog::debug($this->controllerFileName . ' IS REGISTERED : ' . isset(\Phoenix\TAutoloader::getRegisteredCode($this->controllerFileName)), __FILE__, __LINE__);
        
        //\Phoenix\Log\TLog::debug('PARSE FILE : ' . $this->viewFileName, __FILE__, __LINE__);
        //\Phoenix\Log\TLog::debug('GET CODE FILE : ' . $this->controllerFileName, __FILE__, __LINE__);
//        $this->viewHtml = $this->redis->mget($templateName);
//        $this->viewHtml = $this->viewHtml[0];

        $this->viewHtml = file_get_contents($this->viewFileName, FILE_USE_INCLUDE_PATH);
//        $this->redis->mset($templateName, $this->viewHtml);
        //\Phoenix\Log\TLog::debug('HTML VIEW : [' . substr($this->viewHtml, 0, (strlen($this->viewHtml) > 25) ? 25 : strlen($this->viewHtml)) . '...]');
        $doc = new TXmlDocument($this->viewHtml);
        $doc->matchAll();
        if($doc->getCount() > 0) {
            // Il y a des éléments à traiter
            $this->_dirty = true;
            $declarations = $this->writeDeclarations($doc);
            $this->creations = $declarations->creations;
            $this->additions = $declarations->additions;
            $this->afterBinding = $declarations->afterBinding;
            $this->viewHtml = $this->writeHTML($doc, $this->viewHtml);

            //\Phoenix\Log\TLog::debug('CACHE FILE : ' . $this->cacheFileName, __FILE__, __LINE__);
        }
        
        $code = TRegistry::getRegisteredCode($this->controllerFileName);
        // On stocke le code pars� dans un fichier pour ne plus avoir � le parser � la prochaine demande.
        $code = str_replace(CREATIONS_PLACEHOLDER, $this->creations, $code);
        $code = str_replace(ADDITIONS_PLACEHOLDER, $this->additions, $code);
        $code = str_replace(AFTERBINDING_PLACEHOLDER, $this->afterBinding, $code);
        $code = str_replace(HTML_PLACEHOLDER, $this->viewHtml, $code);
        $code = str_replace(DEFAULT_CONTROLLER, DEFAULT_CONTROL, $code);
        $code = str_replace(DEFAULT_PARTIAL_CONTROLLER, DEFAULT_CONTROL, $code);
        $code = str_replace(CONTROLLER, CONTROL, $code);
        $code = str_replace(PARTIAL_CONTROLLER, CONTROL, $code);
        file_put_contents($this->getCacheFileName(), $code);
//        $this->redis->mset($this->preHtmlName, $this->declarations . $this->viewHtml);
        
        // On a généré le code mais on ne l'a pas parsé au sens "exécuté" du terme 
        // donc on sort avec le flag FAUX pour indiquer qu'il doit encore être exécuté
        return false;
        
    }
    

}

