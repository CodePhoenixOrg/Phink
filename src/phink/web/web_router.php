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
 
 namespace Phink\Web;

use Phink\Core\TStaticObject;
 use Phink\TAutoloader;

 /**
 * Description of router
 *
 * @author David
 */
class TWebRouter extends \Phink\Core\TRouter
{
    private $_isCached = false;

    public function __construct($parent)
    {
        $this->application = $parent->getApplication();
        $this->commands = $this->application->getCommands();
        $this->authentication = $parent->getAuthentication();
        $this->request = $parent->getRequest();
        $this->response = $parent->getResponse();
        $this->dirName = $parent->getDirName();
        $this->viewIsInternal = $parent->isInternalView();
        $this->path = $parent->getPath();
                
        $this->translation = $parent->getTranslation();
        $this->parameters = $parent->getParameters();
        
    }

    public function translate()
    {
        $isTranslated = false;

        $info = (object) \pathinfo($this->path);
        $this->viewName = $info->filename;
        $this->dirName = $info->dirname;
        $this->viewName = ($this->viewName == '') ? MAIN_VIEW : $this->viewName;
        $this->className = ucfirst($this->viewName);
        
        $this->setNamespace();
        $this->setNames();

        if(file_exists(SRC_ROOT . $this->getPath())) {
            // $this->path = SRC_ROOT . $this->getPath();
            $isTranslated = true;
        }

        if(file_exists(SITE_ROOT . $this->getPath())) {
            // $this->path = SITE_ROOT . $this->getPath();
            $isTranslated = true;
        }

        $this->_isCached = file_exists($this->getCacheFileName());

        return $this->_isCached || $isTranslated;
    }

    public function dispatch()
    {
        if ($this->_isCached) {
            // $view = new \Phink\MVC\TView($this);
            // $view->parse();
            TAutoloader::loadCachedFile($this);

            return true;
        }

//        $modelClass = ($include = TAutoloader::includeModelByName($this->viewName)) ? $include['type'] : DEFALT_MODEL;
//        include $include['file'];
//        $model = new $modelClass();
        $include = $this->includePrimaryController();

        $view = new \Phink\MVC\TView($this);
        $view->parse();
        
        if (file_exists($view->getCacheFileName())) {
            TAutoloader::loadCachedFile($view);
            return true;
        }
        
        return true;
    }

    public function setNamespace() : void
    {
        if (strstr(SERVER_NAME, 'localhost')) {
            $this->namespace = CUSTOM_NAMESPACE;
        } else {
            $sa = explode('.', SERVER_NAME);
            array_pop($sa);
            if (count($sa) == 2) {
                array_shift($sa);
            }
            $this->namespace = str_replace('-', '_', ucfirst($sa[0]));
        }
        $this->namespace .= '\\Controllers';
    }
    
    public function includeController()
    {
        $result = false;
        
        $result = TAutoloader::includeClass($this->controllerFileName, RETURN_CODE);
        if (!$result) {
            if ($this->getRequest()->isAJAX() && $this->request->isPartialView()) {
                $result = TAutoloader::includeDefaultPartialController($this->namespace, $this->className);
            } else {
                $result = TAutoloader::includeDefaultController($this->namespace, $this->className);
            }

            \Phink\Core\TRegistry::setCode($this->controllerFileName, $result['code']);
        }

        return $result;
    }
    
    public function includePrimaryController()
    {
        if ($this->getRequest()->isAJAX() && $this->request->isPartialView()) {
            $result = TAutoloader::includeDefaultController($this->namespace, $this->className);
            \Phink\Core\TRegistry::setCode($this->controllerFileName, $result['code']);
        } else {
            $result = $this->includeController();
        }
        
        return $result;
    }
}
