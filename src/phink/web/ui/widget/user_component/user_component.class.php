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
 
namespace Phink\Web\UI\Widget\UserComponent;

use Phink\Registry\TRegistry;
use Phink\Web\UI\TCustomControl;
use Phink\Core\IObject;
use Phink\TAutoloader;

class TUserComponent extends TCustomControl
{

    protected $componentType = '';
    protected $componentId = '';

    public function __construct(IObject $parent)
    {
        parent::__construct($parent);

        $this->componentIsInternal = $parent->isInternalComponent();
        $this->dirName = $parent->getDirName();
        $this->path = $parent->getPath();
    }

    public function setComponentType(string $value) : void
    {
        $this->componentType = $value;
    }
    
    public function setComponentId(string $value) : void
    {
        $this->componentId = $value;
    }

    public function render() : void 
    {
        $this->setNames($this->componentType);
        $names = [
            $this->isInternalComponent() ? 'YES' : 'NO',
            $this->getPath(),
            $this->getDirName(),
            $this->getModelFileName(),
            $this->getViewFileName(),
            $this->getCssFileName(),
            $this->getControllerFileName(),
            $this->getJsControllerFileName() 
        ];

        foreach ($names as $name) {
            echo $name . '<br />'; 
        }

        $code = '';

        //$this->setCacheFileName();
        if(file_exists($this->getCacheFileName())) {
            list($namespace, $className, $code) = TAutoloader::getClassDefinition($this->cacheFileName);
            
            include $this->cacheFileName;

            $fqClassName = $namespace . '\\' . $className;

            $class = new $fqClassName($this->getParent());
            $class->render();
        }

        if(!file_exists($this->getCacheFileName())) {
            list($controllerFileName, $className, $code) = $this->includeController();

            include $controllerFileName;
    
            $class = new $className($this->getParent());
            $class->render();
        }
        
    }

    public function includeController(): ?array
    {
        $file = ''; $type = ''; $code = '';

        $result = TAutoloader::includeClass($this->controllerFileName, RETURN_CODE | INCLUDE_FILE);
        if($result !== null) {
            list($file, $type, $code) = $result;
        }
        if ($result === null) {
            list($file, $type, $code) = TAutoloader::includeDefaultPartialController($this->namespace, $this->className);

            TRegistry::setCode($this->controllerFileName, $code);
        }

        return [$file, $type, $code];
    }

}