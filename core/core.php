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

namespace Phink\Core;

require_once 'constants.php';

if(!file_exists('js_builder.lock')) {
    include 'phink/js/js_builder.php';
    file_put_contents('js_builder.lock', date('Y-m-d h:i:s'));
}

if(!file_exists('css_builder.lock')) {
    include 'phink/css/css_builder.php';
    file_put_contents('css_builder.lock', date('Y-m-d h:i:s'));
}

include 'phink/phink_builder.php';

require_once 'phink/autoloader.php';
\Phink\TAutoLoader::register();