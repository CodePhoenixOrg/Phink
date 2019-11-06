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

use Phink\Core\IObject;
use Phink\TAutoloader;
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

    public function setComponentType(string $value): void
    {
        $this->componentType = $value;
    }

    public function setComponentId(string $value): void
    {
        $this->componentId = $value;
    }

    public function render(): void
    {
        $mvcFileNames = $this->getMvcFileNamesByTypeName($this->componentType);

        foreach ($mvcFileNames as $key => $name) {
            self::getLogger()->debug('MVC FILE NAMES::' . $key . '::' . $name);
        }

        $cachedFileName = $mvcFileNames['cacheFileName'];
        $controllerFileName = $mvcFileNames['controllerFileName'];

        $code = '';
        $fqClassName = '';

        if (file_exists($cachedFileName)) {
            list($namespace, $className, $code) = TAutoloader::getClassDefinition($cachedFileName);

            $controllerFileName = $cachedFileName;
            $fqClassName = $namespace . '\\' . $className;
        }

        if (!file_exists($cachedFileName)) {
            list($controllerFileName, $fqClassName, $code) = TAutoloader::includeClass($controllerFileName, RETURN_CODE | INCLUDE_FILE);
        }
        include $controllerFileName;
        self::getLogger()->debug('USER COMPONENT CACHED FILE EXISTS::' . (file_exists($cachedFileName) ? 'TRUE' : 'FALSE'));

        $class = new $fqClassName($this->getParent());
        $class->render();

    }

}
