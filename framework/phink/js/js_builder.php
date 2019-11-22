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
 
namespace Phink\JavaScript;
 
class JsBuilder {

    public static function build () : void
    {
        $destdir = DOCUMENT_ROOT . 'js' . DIRECTORY_SEPARATOR;

        $js_filename = $destdir . '_3rdparty.js';

        $srcdir = SITE_ROOT . 'vendor' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR;
        
        $js_content = '';

        $filenames = [
                'jquery/jquery.js'
            ,   'jqueryui/jquery-ui.js'
            ,   'bootstrap/js/bootstrap.js'
        ];

        foreach ($filenames as $filename) {
            $js_content .= file_get_contents($srcdir . $filename, FILE_USE_INCLUDE_PATH);
        }
        
        $srcdir =  __DIR__ . DIRECTORY_SEPARATOR . 'thirdparty' . DIRECTORY_SEPARATOR;

//            ,   'jquery.ui.touch-punch.min.js'
        
        $filenames = [
                'php.default.min.js'
//            ,   'jquery.mousewheel.min.js'

        ];

        foreach ($filenames as $filename) {
            $js_content .= file_get_contents($srcdir . $filename, FILE_USE_INCLUDE_PATH);
        }
        
        file_put_contents($js_filename, $js_content);

//        $filenames = [
//                'core/debug.js'
//            ,   'core/console.js'
//            ,   'jphink.js'
//        ];
//
//        $srcdir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
//        
//        $js_content = '';
//
//        foreach ($filenames as $filename) {
//            $filename = str_replace("/", DIRECTORY_SEPARATOR, $filename);    
//            $js_content = file_get_contents($srcdir . $filename);
//            $info = explode(DIRECTORY_SEPARATOR, $filename);
//            $filename = array_pop($info);
//            file_put_contents($destdir . $filename, $js_content);
//        }
//
    }

}
