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

use Phink\Web\IHttpTransport;
use Phink\Web\TRequest;
use Phink\Web\TResponse;
use Phink\Core\TObject;

class TActionInfo extends TObject implements IHttpTransport
{

    use \Phink\Web\THttpTransport;

    protected $data = [];

    public function __construct(IHttpTransport $parent)
    {
        parent::__construct($parent);
    
        // $this->application = $parent->getApplication();
        // $this->commands = $this->application->getCommands();
        $this->authentication = $parent->getAuthentication();
        $this->request = $parent->getRequest();
        $this->response = $parent->getResponse();
        $this->twigEnvironment = $parent->getTwigEnvironment();
        // $this->parameters = $parent->getParameters();
    }

    public static function twig(IHttpTransport $parent, string $view, array $dictionary) : TActionInfo
    {
        $action = new TActionInfo($parent);
        $action->setTwig($view, $dictionary);

        return $action;
    }

    public static function set(IHttpTransport $parent, string $key, $value) : TActionInfo
    {
        $action = new TActionInfo($parent);
        $action->setData($key, $value);

        return $action;
    }

    public function setTwig(array $dictionary): void
    {
        $viewName = $this->getParent()->getViewName() . PREHTML_EXTENSION;
        $this->setTwigByName($viewName, $dictionary);
    }

    public function setTwigByName(string $viewName, array $dictionary): void
    {
        $html = $this->renderTwigByName($viewName, $dictionary);
        $this->data['twig'] = $html;
    }

    public function setData(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function getData() : array
    {
        return $this->data;
    }
}