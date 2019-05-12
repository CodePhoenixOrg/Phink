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
 
 
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Phink\MVC;

use \Phink\Core\TRouter;

/**
 * Description of view
 *
 * @author david
 */
class TView extends TCustomView
{
    //put your code here
    
    public function __construct(\Phink\Web\IWebObject $parent)
    {

        parent::__construct($parent);
        
        $this->authentication = $parent->getAuthentication();
        $this->request = $parent->getRequest();
        $this->response = $parent->getResponse();
        $this->application = $parent->getApplication();
        $this->commands = $this->application->getCommands();
        $this->parameters = $parent->getParameters();
        $this->viewName = $parent->getViewName();
        $this->viewIsInternal = $parent->isInternalView();
        $this->path = $parent->getPath();

        $this->cloneNamesFrom($parent);
        $this->setCacheFileName();
        $this->cacheFileName = $parent->getCacheFileName();

    }

}
