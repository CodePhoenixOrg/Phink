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
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Phink\Rest;

/**
 * Description of controller
 *
 * @author David
 */
class TRestController extends \TStaticObject
{
    use THttpTransport;
    //put your code here

    public function __construct(TRestApplication $app)
    {
        $this->authentication = $app->getAuthentication();
        $this->request = $app->getRequest();
        $this->response = $app->getResponse();
    }

    public function head() {}
    public function get() {}
    public function post() {}
    public function put() {}
    public function patch() {}
    public function delete() {}

  
}
