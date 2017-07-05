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

namespace Phink\CascadingStyleSheet;

class CssBuilder {

    public static function deltree($path) {
        $class_func = array(__CLASS__, __FUNCTION__);
        return is_file($path) ?
                @unlink($path) :
                array_map($class_func, glob($path.'/*')) == @rmdir($path);
    }

    public static function build () {
        $theme = 'base';

        $images = [
                'jqueryui/themes/' . $theme . '/images/ui-bg_flat_0_aaaaaa_40x100.png'
            ,   'jqueryui/themes/' . $theme . '/images/ui-icons_444444_256x240.png'
            ,   'jqueryui/themes/' . $theme . '/images/ui-icons_555555_256x240.png'
            ,   'jqueryui/themes/' . $theme . '/images/ui-icons_777620_256x240.png'
            ,   'jqueryui/themes/' . $theme . '/images/ui-icons_777777_256x240.png'
            ,   'jqueryui/themes/' . $theme . '/images/ui-icons_cc0000_256x240.png'
            ,   'jqueryui/themes/' . $theme . '/images/ui-icons_ffffff_256x240.png'
        ];
        
        $fonts = [
                'bootstrap/fonts/glyphicons-halflings-regular.eot'
            ,   'bootstrap/fonts/glyphicons-halflings-regular.svg'
            ,   'bootstrap/fonts/glyphicons-halflings-regular.ttf'
            ,   'bootstrap/fonts/glyphicons-halflings-regular.woff'
            ,   'bootstrap/fonts/glyphicons-halflings-regular.woff2'
            ,   'font-awesome/fonts/FontAwesome.otf'
            ,   'font-awesome/fonts/fontawesome-webfont.eot'
            ,   'font-awesome/fonts/fontawesome-webfont.svg'
            ,   'font-awesome/fonts/fontawesome-webfont.ttf'
            ,   'font-awesome/fonts/fontawesome-webfont.woff'
            ,   'font-awesome/fonts/fontawesome-webfont.woff2'
        ];        

        $filenames = [
                'jqueryui/themes/' . $theme . '/jquery-ui.css'
            ,   'bootstrap/css/bootstrap.css'
            ,   'bootstrap/css/bootstrap-theme.css'
            ,   'font-awesome/css/font-awesome.css'
        ];

//        $path = explode(DIRECTORY_SEPARATOR, __DIR__);
//        array_pop($path);
//        if(strstr(__DIR__, 'vendor' . DIRECTORY_SEPARATOR . 'phink' . DIRECTORY_SEPARATOR . 'phink')) {
//            array_pop($path);
//            array_pop($path);
//        }
//        $vendor = implode(DIRECTORY_SEPARATOR, $path) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR;
        

        $srcdir = SITE_ROOT . 'vendor' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR;


        //$srcdir =  dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..'  . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'javascript' . DIRECTORY_SEPARATOR . 'thirdparty' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR;
        //
        //$filenames = [
        //    ,   'jquery-ui.structure.css'
        ////    ,   'jquerysctipttop.css'
        ////    ,   'multiaccordion.jquery.css'
        ////    ,   'prettify.css'
        ////    ,   'drag-and-drop.css'
        //];

        $destdir = DOCUMENT_ROOT . 'css' . DIRECTORY_SEPARATOR;

        $css_filename = $destdir . '_3rdparty.css';

        $css_content = '';
        foreach ($filenames as $filename) {
            $filename = str_replace("/", DIRECTORY_SEPARATOR, $filename);
            $css_content .= file_get_contents($srcdir . $filename, FILE_USE_INCLUDE_PATH);
        }

//        if(!file_exists($destdir . 'images' . DIRECTORY_SEPARATOR)) {
//            mkdir($destdir . 'images', 0755);
//        } else {
//            $dir = $destdir . 'images';
//            self::deltree($dir);
//        }

        foreach ($images as $filename) {
            $filename = str_replace("/", DIRECTORY_SEPARATOR, $filename);
            $imagePath = explode(DIRECTORY_SEPARATOR, $filename);
            $image = array_pop($imagePath);
            copy($srcdir . $filename, $destdir . 'images' . DIRECTORY_SEPARATOR . $image);
        }

//        if(!file_exists(DOCUMENT_ROOT . 'fonts' . DIRECTORY_SEPARATOR)) {
//            mkdir(DOCUMENT_ROOT . 'fonts', 0755);
//        } else {
//            $dir = DOCUMENT_ROOT . 'fonts';
//            self::deltree($dir);
//        }
        
        foreach ($fonts as $filename) {
            $filename = str_replace("/", DIRECTORY_SEPARATOR, $filename);
            $fontPath = explode(DIRECTORY_SEPARATOR, $filename);
            $font = array_pop($fontPath);
            copy($srcdir . $filename, DOCUMENT_ROOT . 'fonts' . DIRECTORY_SEPARATOR . $font);
        }        

        file_put_contents($css_filename, $css_content);
        
    }
    
}
