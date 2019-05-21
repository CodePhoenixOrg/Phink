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
 
 namespace Phink\Web\UI;

use Phink\Core\TObject;

class TControl extends TCustomControl
{

    protected $model = NULL;
    protected $innerHtml = '';
    protected $viewHtml = '';
    protected $isDreclared = false;

    public function __construct(TObject $parent)
    {
        parent::__construct($parent);

        $this->parameters = $parent->getParameters();
        $this->application = $parent->getApplication();
        $this->commands = $this->application->getCommands();
        $this->path = $this->getPath();
        
        $this->cloneNamesFrom($parent);
        $this->setCacheFileName();
        $this->cacheFileName = $parent->getCacheFileName();
        
        $this->className = $this->getType();
        $this->viewName = lcfirst($this->className);
        
        $include = \Phink\TAutoloader::includeModelByName($this->viewName);
        $model = SRC_ROOT . $include['file'];
        if(file_exists($model)) {
            include $model;
            $modelClass = $include['type'];

            $this->model = new $modelClass();        
        }
        
        $this->authentication = $parent->getAuthentication();
        $this->request = $parent->getRequest();
        $this->response = $parent->getResponse();        
    }

    public function getModel()
    {
        return $this->model;
    }
       
    public function getInnerHtml()
    {
        return $this->innerHtml;
    }
    
    public function view($html)
    {
        $this->viewHtml = $html;
        //include "data://text/plain;base64," . base64_encode($this->viewHtml);        
    }

    public function renderView()
    {
        include "data://text/plain;base64," . base64_encode($this->viewHtml);
    }
    
    public function createObjects() {}
    
    public function declareObjects() {}

    public function afterBinding() {}
    
    public function displayHtml() {}
    
    public function getViewHtml()
    {
        // if(isset($_REQUEST['PHPSESSID'])) {
        //     self::$logger->debug(__METHOD__ . '::PHPSESSID::' . $_REQUEST['PHPSESSID']);
        // }
        ob_start();
        if(!$this->isDreclared) {
            //$this->createObjects();
            $this->declareObjects();
//            $this->partialLoad();
        }
        $this->displayHtml();
        $html = ob_get_clean();
        $this->unload();

/*        
        $cachedJsController = RUNTIME_DIR . \Phink\TAutoloader::cacheJsFilenameFromView($this->viewName);
        if(file_exists($cachedJsController)) {
            $jsCode = file_get_contents($cachedJsController);
            $html .= PHP_EOL . "?>" .PHP_EOL . $jsCode . PHP_EOL;
            
            $this->response->addScript($cachedJsController);
        }
*/        
        if(file_exists(SRC_ROOT . $this->getJsControllerFileName())) {
            $cacheJsFilename = \Phink\TAutoloader::cacheJsFilenameFromView($this->viewName);
            if(!file_exists(DOCUMENT_ROOT . $cacheJsFilename)) {
                copy(SRC_ROOT . $this->getJsControllerFileName(), DOCUMENT_ROOT . $cacheJsFilename);
            }
            $this->response->addScript($cacheJsFilename);
        }
        $this->response->setData('view', $html);

    }   
    
    public function render()
    {
        $this->init();
        $this->createObjects();
        $this->beforeBinding();
        $this->declareObjects();
        $this->afterBinding();
        $this->isDreclared = true;
        if($this->viewHtml) {
            $this->renderView();
        } else {
            $this->displayHtml();
        }
        $this->renderHtml();
        $this->unload();
    }
    
    public function perform()
    {
        $this->init();
        $this->createObjects();
        if($this->getRequest()->isAJAX()) {
            try {
                $actionName = $this->actionName;

                $params = $this->validate($actionName);
                $this->invoke($actionName, $params);

                $this->beforeBinding();
                $this->declareObjects();
                $this->afterBinding();

                if($this->request->isPartialView()
                || ($this->request->isView() && $actionName !== 'getViewHtml')) {
                    $this->getViewHtml();
                }
            } catch (\BadMethodCallException $ex) {
                $this->response->setData('error', $ex->getMessage());
            }

            $this->response->sendData();
        } else {
            $this->load();
            $this->beforeBinding();
            $this->declareObjects();
            $this->afterBinding();
            
            if($this->viewHtml) {
                $this->renderView();
            } else {
                $this->displayHtml();
            }
            $this->unload();
        }        
    }
    
    public function __destruct()
    {
        unset($this->model);
    }

}
