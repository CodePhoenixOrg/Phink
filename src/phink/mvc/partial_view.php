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

 use Phink\Core\IObject;
 use Phink\Web\IWebObject;
 use Phink\MVC\TCustomView;

class TPartialView extends TCustomView
{
    public function __construct(IWebObject $father, IWebObject $parent)
    {
        $this->motherView = $father;
        $this->className = $parent->getType();
        parent::__construct($parent);

        $this->setViewName();
        $this->setNamespace();
        $this->setNames();
                
        $this->request = $parent->getRequest();
        $this->response = $parent->getResponse();
    }

    public function setViewName($viewName = null) : void
    {
        $this->viewName = lcfirst($this->className);
    }
}
