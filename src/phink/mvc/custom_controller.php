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

use Phink\Web\IWebObject;
use Phink\Web\UI\TCustomControl;
use TActionInfo;

abstract class TCustomController extends TCustomControl
{

    protected $innerHtml = '';
    protected $creations = '';
    protected $declarations = '';
    protected $beforeBinding = '';
    protected $afterBinding = '';
    protected $viewHtml = '';
    protected $model = null;
    protected $view = null;
    private $_type = '';
    
    public function __construct(IWebObject $parent)
    {

        parent::__construct($parent);
        
        $this->application = $parent->getApplication();
        $this->commands = $this->application->getCommands();
        $this->authentication = $parent->getAuthentication();
        $this->request = $parent->getRequest();
        $this->response = $parent->getResponse();        
        $this->parameters = $parent->getParameters();
        $this->path = $this->getPath();
        $this->twigEnvironment = $this->getTwigEnvironment();

        $this->cloneNamesFrom($parent);

        // $this->setCacheFileName();

    }

    public function getInnerHtml()
    {
        return $this->innerHtml;
    }
    
    public function clearInnerHtml()
    {
        $this->innerHtml = '';
    }

    public function getView()
    {
        return $this->view;
    }
    
    public function getModel()
    {
        return $this->model;
    }
    
    // public function view($html)
    // {
    //     $this->viewHtml = $html;
    //     include "data://text/plain;base64," . base64_encode($this->viewHtml);        
    // }
       
    public function parse()
    {
        $this->cacheFileName = $this->view->getCacheFileName();
        self::$logger->debug('CACHE FILE NAME IF EXISTS : ' . $this->cacheFileName, __FILE__, __LINE__);

        $isAlreadyParsed = file_exists($this->getCacheFileName());
        self::$logger->debug('CACHED FILE EXISTS : ' . $isAlreadyParsed ? 'TRUE' : 'FALSE', __FILE__, __LINE__);

        if(!$isAlreadyParsed) {

            $this->_type = $this->view->parse();
            $this->creations = $this->view->getCreations();
            $this->declarations = $this->view->getAdditions();
            $this->viewHtml = $this->view->getViewHtml();
 
        }
        
        return $isAlreadyParsed;
    }

    public function renderCreations()
    {
        if(!empty($this->creations)) {
            /*
             * include "data://text/plain;base64," . base64_encode('<?php' . $this->creations . '?>');
             */
            eval($this->creations);
        }
    }

    public function renderDeclarations()
    {
        if(!empty($this->declarations)) {
             /* 
             * include "data://text/plain;base64," . base64_encode('<?php' . $this->declarations . '?>');
             */
            eval($this->declarations);
        }
    }

    /*
    public function renderAfterBinding()
    {
        if(!empty($this->afterBinding)) {
            include "data://text/plain;base64," . base64_encode('<?php' . $this->afterBinding . '?>');
        }
    }
    */

    public function renderView()
    {
//        include "data://text/plain;base64," . base64_encode($this->viewHtml);
        eval('?>' . $this->viewHtml . '<?php ');
    }

    public function renderedHtml()
    {
//        include "data://text/plain;base64," . base64_encode($this->innerHtml);
//        echo $this->innerHtml;
        eval('?>' . $this->innerHtml . '<?php ');
    }

}