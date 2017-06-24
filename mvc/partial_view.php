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

use Phink\MVC\TCustomView;
use Phink\Core\TRegistry;

class TPartialView extends TCustomView 
{

    public function __construct(\Phink\Core\TObject $father, \Phink\Core\TObject $parent)
    {
        $this->parentView = $father;
        $this->className = $parent->getType();
        parent::__construct($parent);
//        $this->depth += $this->view->getDepth();
        //self::$logger->debug('FATHER TYPE <> PARENT TYPE : ' . $father->getType() . ' <> ' . $this->className, __FILE__, __LINE__);
        $this->request = $parent->getRequest();
        $this->response = $parent->getResponse();        

//        if($this->depth > 8) {
//            throw new \Exception("Partial views are emboxed beyond level 8. Please check that you did'nt embox a partial view in itself. This may turn in an infinite loop.");
//        }
    }

    public function setViewName($viewName = null)
    {
        $this->viewName = lcfirst($this->className);
    }

    public function setNames()
    {
        
        $this->controllerFileName = 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $this->viewName . CLASS_EXTENSION;
        $this->viewFileName = 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $this->viewName . PREHTML_EXTENSION;
        
        if(!file_exists($this->viewFileName)) {

            if($info = TRegistry::classInfo($this->className))
            {
                $this->viewName = \Phink\TAutoloader::classNameToFilename($this->className);
                if($info->hasTemplate) {
                    $this->viewFileName = ROOT_PATH . $info->path . $this->viewName . PREHTML_EXTENSION;
                } else {
                    $this->viewFileName = '';
                }
                $this->controllerFileName = ROOT_PATH . $info->path . $this->viewName . CLASS_EXTENSION;
                $this->className = $info->namespace . '\\' . $this->className;
            }

            $this->getCacheFileName();
        }
                
    }

}
