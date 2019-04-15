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

    public static function implicitLink()
    {
        return SERVER_ROOT . REQUEST_URI;
    }

    public static function cache($filename, $content)
    {
        $filename = CACHE_DIR . DIRECTORY_SEPARATOR . $filename;
        $filename = \Phink\Utils\TFileUtils::filePath($filename);
        file_put_contents($filename, $content);
    } 

    public function getStaticFileName()
    {
        $filename = CACHE_DIR . ((REQUEST_URI == '/') ? MAIN_PAGE : REQUEST_URI);
        
        if(strstr(HTTP_ACCEPT, 'json')) {
            $filename = str_replace('.html', '.json', $filename);
        }
        $filename = \Phink\Utils\TFileUtils::filePath($filename);
        $p = strpos($filename, '?');
        $filename = str_replace('?', '_', str_replace('&', '_', str_replace('.', '_', str_replace('=', '_', $filename)))) . HTML_EXTENSION;
        
        return $filename;
    }

    public static function create($params = array())
    {
        (new TStaticApplication())->run($params);
    }

    public function run($params)
    {
        if(strstr(HTTP_ACCEPT, 'partialview')) {
            parent::run($params);
        } else {
            if($this->validateToken()) {
                $filename = $this->getStaticFileName();
                //self::$logger->debug('HTTP_ACCEPT : ' . HTTP_ACCEPT, __FILE__, __LINE__);
                if (!file_exists($filename)) {
                    ob_start();
                    parent::create();
                    $contents = ob_get_clean();
                    if(!strstr($filename, '.json')) {
                        file_put_contents($filename, $contents);
                    }
                    echo $contents;
                } else {
                    include $filename;
                }
            }
        }
    }
}
