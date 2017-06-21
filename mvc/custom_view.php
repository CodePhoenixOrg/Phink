<?php
/*
 * Copyright (C) 2016 David Blanchard
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
 
 namespace Phink\MVC;

use Phink\Core\TObject;
use Phink\Xml\TXmlDocument;
use Phink\Core\TRegistry;

abstract class TCustomView extends \Phink\Web\UI\TCustomControl
{
    use \Phink\Web\UI\TCodeGenerator {
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
        ////self::$logger->debug($this->controllerFileName . ' IS REGISTERED : ' . isset(\Phink\TAutoloader::getCode($this->controllerFileName)), __FILE__, __LINE__);
        
        self::$logger->debug('PARSE FILE : ' . $this->viewFileName, __FILE__, __LINE__);
//        $this->viewHtml = $this->redis->mget($templateName);
//        $this->viewHtml = $this->viewHtml[0];
        if (file_exists(SITE_ROOT . $this->viewFileName)) {
            $this->viewHtml = file_get_contents(SITE_ROOT . $this->viewFileName);
        } else {
            $this->viewHtml = file_get_contents($this->viewFileName, FILE_USE_INCLUDE_PATH);
        }
        
//        $this->redis->mset($templateName, $this->viewHtml);
        //self::$logger->debug('HTML VIEW : [' . substr($this->viewHtml, 0, (strlen($this->viewHtml) > 25) ? 25 : strlen($this->viewHtml)) . '...]');
        $doc = new TXmlDocument($this->viewHtml);
        $doc->matchAll();
        if($doc->getCount() > 0) {
            // Il y a des éléments à traiter
            $this->_dirty = true;
            $declarations = $this->writeDeclarations($doc, $this);
            $this->creations = $declarations->creations;
            $this->additions = $declarations->additions;
            $this->afterBinding = $declarations->afterBinding;
            $this->viewHtml = $this->writeHTML($doc, $this);

            //self::$logger->debug('CACHE FILE : ' . $this->cacheFileName, __FILE__, __LINE__);
        }
        
        $code = TRegistry::getCode($this->controllerFileName);
        // We store the parsed code in a file so that we know it's already parsed on next request.
        $code = str_replace(CREATIONS_PLACEHOLDER, $this->creations, $code);
        $code = str_replace(ADDITIONS_PLACEHOLDER, $this->additions, $code);
//        $code = str_replace(AFTERBINDING_PLACEHOLDER, $this->afterBinding, $code);
        $code = str_replace(HTML_PLACEHOLDER, $this->viewHtml, $code);
        $code = str_replace(DEFAULT_CONTROLLER, DEFAULT_CONTROL, $code);
        $code = str_replace(DEFAULT_PARTIAL_CONTROLLER, DEFAULT_CONTROL, $code);
        $code = str_replace(CONTROLLER, CONTROL, $code);
        $code = str_replace(PARTIAL_CONTROLLER, CONTROL, $code);
        if(!empty(trim($code))) {
            file_put_contents($this->getCacheFileName(), $code);
        }
      
//        $this->redis->mset($this->preHtmlName, $this->declarations . $this->viewHtml);
        
        
        // We generate the code, but we don't flag it as parsed because it was not "executed"
        return false; //$include['type'];
        
    }
    

}

