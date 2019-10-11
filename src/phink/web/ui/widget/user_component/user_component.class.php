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

use Phink\TAutoloader;
use Phink\Core\IObject;
use Phink\MVC\TActionInfo;
use Phink\MVC\TCustomView;
use Phink\MVC\TPartialView;
use Phink\MVC\TModel;
use Phink\Registry\TRegistry;
use Phink\Web\TWebObject;
use Phink\Web\UI\TCustomControl;

class TUserComponent extends TCustomControl
{

    protected $componentType = '';
    protected $componentId = '';

    public function __construct(IObject $parent)
    {
        parent::__construct($parent);

        $this->clonePrimitivesFrom($parent);

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
        $mvcFileNames = $this->getMvcFileNamesByTypeName($this->componentType);

        foreach ($mvcFileNames as $key => $name) {
            echo $key . '::' . $name . '<br />'; 
        }

        $cachedFileName = $mvcFileNames['cacheFileName'];
        $controllerFileName = $mvcFileNames['controllerFileName'];

        $code = '';

        if(file_exists($cachedFileName)) {
            list($namespace, $className, $code) = TAutoloader::getClassDefinition($cachedFileName);
            
            include $cachedFileName;

            $fqClassName = $namespace . '\\' . $className;

            $class = new $fqClassName($this->getParent());
            $class->render();
        }

        if(!file_exists($cachedFileName)) {
            
            list($controllerFileName, $className, $code) = TAutoloader::includeClass($controllerFileName, RETURN_CODE | INCLUDE_FILE);

            include $controllerFileName;
    
            $class = new $className($this->getParent());
            $class->render();
        }
        
    }

}