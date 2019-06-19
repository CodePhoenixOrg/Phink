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
 
 
namespace Phink\Web;

//require_once 'phink/web/web_application.php';

/**
 * Description of program
 *
 * @author david
 */
class TStaticApplication extends TWebApplication
{
    public static function implicitLink() :  string
    {
        return SERVER_ROOT . REQUEST_URI;
    }

    public function getStaticFileName() : string
    {
        $extension = HTML_EXTENSION;
        $filename = CACHE_DIR . ((REQUEST_URI == '/') ? MAIN_PAGE : REQUEST_URI);
        
        $filename = \Phink\Utils\TFileUtils::filePath($filename);
        $p = strpos($filename, '?');
        $filename = str_replace('?', '_', str_replace('&', '_', str_replace('.', '_', str_replace('=', '_', $filename)))) . $extension;
        
        return $filename;
    }

    public static function create(...$params) : void
    {
        (new TStaticApplication())->run($params);
    }

    public function run(...$params) : bool
    {
        $filename = $this->getStaticFileName();
        $pinfo = (object) pathinfo($filename);
        // $extension = '.' . $pinfo->extension;

        // if (strstr(HTTP_ACCEPT, 'partialview')
        // || strstr(HTTP_ACCEPT, 'application/javascript')
        // || strstr(HTTP_ACCEPT, '*/*')
        // || $extension == JSON_EXTENSION 
        // || $extension == JS_EXTENSION) {
        // }

        // if (strstr(HTTP_ACCEPT, 'text/html,application/xhtml+xml,aplication/xml')) {
        if (strstr(HTTP_ACCEPT, 'text/html')) {
            //self::$logger->debug('HTTP_ACCEPT : ' . HTTP_ACCEPT, __FILE__, __LINE__);
            if (!file_exists($filename)) {
                ob_start();
                parent::run($params);
                $contents = ob_get_clean();

                if (strstr($pinfo->dirname, '/') && !file_exists($pinfo->dirname)) {
                    mkdir($pinfo->dirname, 0755, true);
                }
                file_put_contents($filename, $contents);
            // } else {

            }
            include $filename;
        } else {
            parent::run($params);

        }

        return true;
    }
}
