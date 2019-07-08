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
 
namespace Phink\JavaScript;
 
class PhinkBuilder {

    public static function build () {

        // $srcdir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'bower_components' . DIRECTORY_SEPARATOR . 'phinkjs' . DIRECTORY_SEPARATOR . 'client' . DIRECTORY_SEPARATOR;
        // $srcdir = SRC_ROOT . 'web' . DIRECTORY_SEPARATOR . 'bower_components' . DIRECTORY_SEPARATOR . 'phinkjs' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'client' . DIRECTORY_SEPARATOR;
        $srcdir = PHINKJS_ROOT;

        $js_filename = DOCUMENT_ROOT . 'phink.js';

        $filenames = [
                'main.js'
            ,   'utils/text.js'
            ,   'core/registry.js'
            ,   'core/object.js'
            ,   'core/url.js'
            ,   'rest/rest.js'
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
            $js_content .= file_get_contents($srcdir . $filename);
        }

        file_put_contents($js_filename, $js_content);

    }

}
