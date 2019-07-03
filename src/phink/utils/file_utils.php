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
 
 namespace Phink\Utils;

use Phink\Web\TRequest;

//require_once 'phink/core/request.php';

/**
* Description of ileutils
*
* @author david
*/
class TFileUtils
{
    //put your code here
    public static function fileExists($filePath)
    {
        $filePath = DOCUMENT_ROOT . $filePath;
        return file_exists($filePath);
    }

    public static function filePath($filename)
    {
        if(PHP_OS == 'WINNT') {
            $filename = str_replace('/', DIRECTORY_SEPARATOR, $filename);
            $filename = str_replace('\\\\', '\\', $filename);
        } else {
            $filename = str_replace('//', DIRECTORY_SEPARATOR, $filename);
        }
        return $filename;
    }

    public static function webPath($filename)
    {
        $filename = str_replace(DIRECTORY_SEPARATOR, WEB_SEPARATOR, $filename);
        $filename = str_replace('//', '/', $filename);

        return $filename;
    }
    
    public static function requireFile($filename)
    {
        $filename = self::filePath($filename);
        if(!strstr($filename, DOCUMENT_ROOT) && strpos($filename, DIRECTORY_SEPARATOR) === 0) {
            $filename = DOCUMENT_ROOT . $filename;
            $filename = self::filePath($filename);
        }
        try {
            //require_once $filename;
            return true;
        }
        catch (Exception $ex) {
            debug('The required file "' . $filename . '" was not found');
            return false;
        }

    }

    public static function loadFileContents($filename)
    {
        
        debug($filename);
        $filename = self::filePath($filename);
        if(strpos($filename, DIRECTORY_SEPARATOR) == 0) {
            $filename = SRC_ROOT . $filename;
            $filename = self::filePath($filename);
        }
        debug($filename);

        try {
            $result = file_get_contents($filename, FILE_USE_INCLUDE_PATH);
        }
        catch (Exception $ex) {
            TApplication::$log->writeLine('The file "' . $filename . '" was not found');
            $result = false;
        }

        return $result;
    }

    public static function makeDirectory($path)
    {
        try {
            mkdir(DOCUMENT_ROOT . $path, 0777, true);
            return true;
        } catch (Exception $ex) {
            TApplication::$log->writeException($ex);
            return false;
        }
    }

    public static function makeStaticFile($filename, $link)
    {
//            TApplication::$log->writeLine($filename);

        $html = file_get_contents($link);
        file_put_contents($filename, $html);

    }
    
    public static function relativePathToAbsolute($path)
    {
        $result = '';
        $array = explode(DIRECTORY_SEPARATOR, $path);
        
        $c = count($array);
        $offset = 1;
        for($i = 0; $i < $c; $i++) {
            if($array[$i] == '..') {
                unset($array[$i]);
                unset($array[$i - $offset]);
                $offset += 2;
            }
        }
        
        
        $result = implode(DIRECTORY_SEPARATOR, $array);
        
        return $result;
    }

    public static function walkTree($path, $filter = [])
    {
        $result = [];
        
        $l = strlen($path);
        
        $dir_iterator = new \RecursiveDirectoryIterator($path);
        $iterator = new \RecursiveIteratorIterator($dir_iterator, \RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($iterator as $file) {
            $fi = pathinfo($file->getPathName());
            
            if ($fi['basename'] ==  '.' || $fi['basename'] == '..') {
                continue;
            }
            
            if (isset($fi['extension'])) {
                if (count($filter) > 0 && in_array($fi['extension'], $filter)) {
                    array_push($result, substr($file->getPathName(), $l));
                } elseif (count($filter) === 0) {
                    array_push($result, substr($file->getPathName(), $l));
                }
            }
        }

        return $result;
    }

    public static function walkTree2(string $path, ?array &$tree)
    {
        $class_func = array(__CLASS__, __FUNCTION__);
        return is_file($path) ?
                @array_push($tree, $path) :
                array_map($class_func, glob($path.'/*'), $tree);
    }

    public static function delTree(string $path) : bool
    {
        $class_func = array(__CLASS__, __FUNCTION__);
        return is_file($path) ?
                @unlink($path) :
                array_map($class_func, glob($path.'/*')) == @rmdir($path);
    }
}
