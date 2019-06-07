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
use Phink\Web\IWebObject;
use Phink\Web\UI\TCustomControl;

abstract class TCustomView extends TCustomControl
{
    use \Phink\Web\UI\TCodeGenerator {
        writeDeclarations as private;
        writeHTML as private;
    }

    protected $router = null;
    protected $viewHtml = '';
    protected $twigHtml = '';
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
    protected $parentView = null;
    protected $parentType = null;

    public function __construct(IWebObject $parent)
    {
        $this->setParent($parent);
        $this->path = $parent->getPath();
        $this->application = $parent->getApplication();
        
        //$this->redis = new Client($this->context->getRedis());
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

    // public function setViewHtml($html)
    // {
    //     $this->viewHtml = $html;
    // }
    
    public function getViewHtml()
    {
        return $this->viewHtml;
    }

    public function setTwigHtml($html)
    {
        $this->twigHtml = $html;
    }
    
    public function getTwigHtml()
    {
        return $this->twigHtml;
    }
    
    public function setTwig(array $dictionary): void
    {
        $viewName = $this->getViewName() . PREHTML_EXTENSION;
        $this->setTwigByName($viewName, $dictionary);
    }

    public function setTwigByName(string $viewName, array $dictionary): void
    {
        $html = $this->renderTwigByName($viewName, $dictionary);
        $this->twigHtml = $html;
    }
    
    public function parse()
    {
        self::$logger->debug($this->viewName . ' IS REGISTERED : ' . (TRegistry::exists('code', $this->controllerFileName) ? 'TRUE' : 'FALSE'), __FILE__, __LINE__);

        // $this->viewHtml = $this->redis->mget($templateName);
        // $this->viewHtml = $this->viewHtml[0];

        if (empty($this->getViewHtml())) {
            if (file_exists(SRC_ROOT . $this->viewFileName) && !empty($this->viewFileName)) {
                self::$logger->debug('PARSE SRC ROOT FILE : ' . $this->viewFileName, __FILE__, __LINE__);

                $this->viewHtml = file_get_contents(SRC_ROOT . $this->viewFileName);
            }
            if (file_exists(SITE_ROOT . $this->viewFileName) && !empty($this->viewFileName)) {
                self::$logger->debug('PARSE SITE ROOT FILE : ' . $this->viewFileName, __FILE__, __LINE__);
    
                $this->viewHtml = file_get_contents(SITE_ROOT . $this->viewFileName);
            }
            if (file_exists(SITE_ROOT . $this->getPath()) && !empty($this->getPath())) {
                $path = $this->getPath();
                if ($path[0] == '@') {
                    $path = str_replace("@" . DIRECTORY_SEPARATOR, SITE_ROOT, $this->getPath());
                } else {
                    $path = SITE_ROOT . $this->getPath();
                }
                self::$logger->debug('PARSE PHINK VIEW : ' . $path, __FILE__, __LINE__);

                $this->viewHtml = file_get_contents($path);
            }
            // else {
            //     self::$logger->debug('PARSE PHINK PLUGIN : ' . $this->getPath(), __FILE__, __LINE__);

            //     $this->viewHtml = file_get_contents(SITE_ROOT . $this->viewFileName, FILE_USE_INCLUDE_PATH);
            // }
        
            // $this->redis->mset($templateName, $this->viewHtml);
            //self::$logger->debug('HTML VIEW : [' . substr($this->viewHtml, 0, (strlen($this->viewHtml) > 25) ? 25 : strlen($this->viewHtml)) . '...]');
            $doc = new TXmlDocument($this->viewHtml);
            $doc->matchAll();

            if ($doc->getCount() > 0) {
                $declarations = $this->writeDeclarations($doc, $this);
                $this->creations = $declarations->creations;
                $this->additions = $declarations->additions;
                $this->afterBinding = $declarations->afterBinding;
                $this->viewHtml = $this->writeHTML($doc, $this);
            }
        }

        if (!TRegistry::exists('code', $this->controllerFileName)) {
            self::$logger->debug('NO NEED TO WRITE CODE: ' . $this->controllerFileName, __FILE__, __LINE__);
            return false;
        }
        
        $code = TRegistry::getCode($this->controllerFileName);
        // We store the parsed code in a file so that we know it's already parsed on next request.
        $code = str_replace(CREATIONS_PLACEHOLDER, $this->creations, $code);
        $code = str_replace(ADDITIONS_PLACEHOLDER, $this->additions, $code);
        $code = str_replace(HTML_PLACEHOLDER, $this->viewHtml, $code);
        $code = str_replace(DEFAULT_CONTROLLER, DEFAULT_CONTROL, $code);
        $code = str_replace(DEFAULT_PARTIAL_CONTROLLER, DEFAULT_CONTROL, $code);
        $code = str_replace(CONTROLLER, CONTROL, $code);
        $code = str_replace(PARTIAL_CONTROLLER, CONTROL, $code);
        if (!empty(trim($code))) {
            self::$logger->debug('SOMETHING TO CACHE : ' . $this->getCacheFileName(), __FILE__, __LINE__);
            file_put_contents($this->getCacheFileName(), $code);
        }
      
//        $this->redis->mset($this->preHtmlName, $this->declarations . $this->viewHtml);
        
        // We generate the code, but we don't flag it as parsed because it was not "executed"
        return false;
    }


}
