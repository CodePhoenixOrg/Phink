<?php

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
            $filename = DOCUMENT_ROOT . $filename;
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

    public static function getDirectory($filePath)
    {
        $result = "";

        (array) $cells = split("/", $filePath);
        $l = count($cells) - 1;
        unset($cells[$l]);
        $result = join("/", $cells) . "/";

        return $result;
    }    
}
