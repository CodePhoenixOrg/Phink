<?php
/*
 * Copyright (C) 2019 David Blanchard
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

use Phink\Core\TRouter;
use Phink\Registry\TRegistry;
use Phink\TAutoloader;
use Phink\MVC\TView;
/**
 * Description of router
 *
 * @author David
 */
class TWebRouter extends TRouter
{
    private $_isCached = false;

    public function __construct($parent)
    {
        $this->clonePrimitivesFrom($parent);

        $this->translation = $parent->getTranslation();

    }

    public function translate(): bool
    {
        $isTranslated = false;

        $info = (object) \pathinfo($this->path);
        $this->viewName = $info->filename;
        $this->dirName = $info->dirname;

        if ($this->componentIsInternal) {
            $this->dirName = dirname($this->dirName, 2);
        }

        $this->className = ucfirst($this->viewName);

        $this->setNamespace();
        $this->setNames();

        if (file_exists(SRC_ROOT . $this->getPath())) {
            // $this->path = SRC_ROOT . $this->getPath();
            $isTranslated = true;
        }

        if (file_exists(SITE_ROOT . $this->getPath())) {
            // $this->path = SITE_ROOT . $this->getPath();
            $isTranslated = true;
        }

        $this->_isCached = file_exists($this->getCacheFileName());

        return $this->_isCached || $isTranslated;
    }

    public function dispatch(): bool
    {

        if($this->componentIsInternal) {
            $dir = SITE_ROOT . $this->dirName . DIRECTORY_SEPARATOR;

            self::getLogger()->debug('BOOTSTRAP PATH::' . $dir . 'bootstrap' . CLASS_EXTENSION);

            if(file_exists($dir . 'bootstrap' . CLASS_EXTENSION)) {
                list($namespace, $className, $classText) = TAutoloader::getClassDefinition($dir . 'bootstrap' . CLASS_EXTENSION);
                include $dir . 'bootstrap' . CLASS_EXTENSION;
    
                $bootstrapClass = $namespace . '\\'  . $className;
    
                $bootstrap = new $bootstrapClass($dir);
                $bootstrap->start();
            }
        }

        if ($this->_isCached) {
            $view = new TView($this);
            $class = TAutoloader::loadCachedFile($view);
            $class->perform();
            return true;
        }

//        $modelClass = ($include = TAutoloader::includeModelByName($this->viewName)) ? $include['type'] : DEFALT_MODEL;
        //        include $include['file'];
        //        $model = new $modelClass();
        $include = $this->includeController();

        $view = new TView($this);
        $view->parse();

        if (file_exists($view->getCacheFileName())) {
            $class = TAutoloader::loadCachedFile($view);
            $class->perform();
            return true;
        }

        return false;
    }

    public function setNamespace(): void
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

    public function includeController(): ?array
    {
        $file = '';
        $type = '';
        $code = '';


        $result = TAutoloader::includeClass($this->controllerFileName, RETURN_CODE);
        if ($result !== null) {
            list($file, $type, $code) = $result;
        }
        if ($result === null) {
            if ($this->getRequest()->isAJAX() && $this->request->isPartialView()) {
                list($file, $type, $code) = TAutoloader::includeDefaultPartialController($this->namespace, $this->className);
            } else {
                list($file, $type, $code) = TAutoloader::includeDefaultController($this->namespace, $this->className);
            }

            TRegistry::setCode($this->controllerFileName, $code);
        }

        return [$file, $type, $code];
    }

}
