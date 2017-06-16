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
 
 
class JsBuilder {

    public static function main () {
        $destdir = DOCUMENT_ROOT . 'js' . DIRECTORY_SEPARATOR;

        $js_filename = $destdir . '_3rdparty.js';

        $path = explode(DIRECTORY_SEPARATOR, __DIR__);
        array_pop($path);
        if(strstr(__DIR__, 'vendor' . DIRECTORY_SEPARATOR . 'phink' . DIRECTORY_SEPARATOR . 'phink')) {
            array_pop($path);
            array_pop($path);
        }
        $vendor = implode(DIRECTORY_SEPARATOR, $path) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR;
        
        $srcdir = $vendor . 'components' . DIRECTORY_SEPARATOR;

        $js_content = '';

        $filenames = [
                'jquery/jquery.js'
            ,   'jquery/jquery-ui.js'
            ,   'bootstrap/js/bootstrap.js'
        ];

        foreach ($filenames as $filename) {
            $js_content .= file_get_contents($srcdir . $filename, FILE_USE_INCLUDE_PATH);
        }
        
        $srcdir =  __DIR__ . DIRECTORY_SEPARATOR . 'thirdparty' . DIRECTORY_SEPARATOR;
        
        $filenames = [
                'php.default.min.js'
            ,   'jquery.ui.touch-punch.min.js'
            ,   'jquery.mousewheel.min.js'
            ,   'widgets.js'

        ];

        foreach ($filenames as $filename) {
            $js_content .= file_get_contents($srcdir . $filename, FILE_USE_INCLUDE_PATH);
        }
        
        file_put_contents($js_filename, $js_content);

        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;

        $js_filename = $destdir . 'code_phoenix.js';

        $filenames = [
                'global.js'
            ,   'core/registry.js'
            ,   'core/utils.js'
            ,   'core/object.js'
            ,   'core/url.js'
            ,   'web/rest.js'
            ,   'web/web_object.js'
            ,   'web/web_application.js'
            ,   'mvc/view.js'
            ,   'mvc/controller.js'
            ,   'web/ui/plugin.js'
            ,   'web/ui/plugin/accordion.js'
            ,   'web/ui/plugin/list.js'
            ,   'web/ui/plugin/table.js'
        ];

        $js_content = '';

        foreach ($filenames as $filename) {
            $js_content .= file_get_contents($dir . $filename);
        }

        file_put_contents($js_filename, $js_content);

        $filenames = [
                'core/debug.js'
            ,   'core/console.js'
            ,   'jphink.js'
        ];

        $js_content = '';

        foreach ($filenames as $filename) {
            $filename = str_replace("/", DIRECTORY_SEPARATOR, $filename);    
            $js_content = file_get_contents($dir . $filename);
            $info = explode(DIRECTORY_SEPARATOR, $filename);
            $filename = array_pop($info);
            file_put_contents($destdir . $filename, $js_content);
        }

    }

}