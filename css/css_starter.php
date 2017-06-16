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

$filenames = [
    'bootstrap.css'
//    ,   'bootstrap-theme.css'
    ,   'jquery-ui.css'
    ,   'jquery-ui.structure.css'
    ,   'jquery-ui.theme.css'    
    ,   'font-awesome.css'
//    ,   'jquerysctipttop.css'
//    ,   'multiaccordion.jquery.css'
//    ,   'prettify.css'
//    ,   'drag-and-drop.css'
];

$path = explode(DIRECTORY_SEPARATOR, __DIR__);
array_pop($path);
array_pop($path);
if(strstr(__DIR__, 'vendor' . DIRECTORY_SEPARATOR . 'phink' . DIRECTORY_SEPARATOR . 'phink')) {
    array_pop($path);
}
$vendor = implode(DIRECTORY_SEPARATOR, $path) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR;

$srcdir = $vendor . 'components' . DIRECTORY_SEPARATOR;

$destdir = DOCUMENT_ROOT . 'css' . DIRECTORY_SEPARATOR;

$css_filename = $destdir . '_3rdparty.css';

$css_content = '';
foreach ($filenames as $filename) {
    $css_content .= file_get_contents($srcdir . $filename, FILE_USE_INCLUDE_PATH);

}

file_put_contents($css_filename, $css_content);
