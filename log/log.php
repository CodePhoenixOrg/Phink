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
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Phink\Log;

//require_once 'constants.php';

/**
 * Description of log
 *
 * @author david
 */


class TLog
{
    //put your code here
    
    private static $_debugLogFile = '';

    private static function _setDebugLogFile()
    {
        if(APP_ROOT == '') {
            self::$_debugLogFile = './logs/debug.log';
        } else {
            self::$_debugLogFile = APP_ROOT . 'logs/debug.log';
        }
    }

    public static function file($filename, $object)
    {
        file_put_contents (DOCUMENT_ROOT . RUNTIME_DIR . $filename . '.log', print_r($object, true) . CR_LF);
    }
    
    public static function dump($message, $object)
    {
        self::debug($message . '::' . print_r($object, true) . CR_LF);
    }

    public static function debug($message, $filename = null, $line = null)
    {
        self::_setDebugLogFile();
        $handle = fopen(self::$_debugLogFile, 'a');

        if(APP_ROOT) {
            $filename = substr($filename, strlen(APP_ROOT));
        }
        $message = date('Y-m-d h:i:s') . ((isset($filename)) ? ":$filename" : '') . ((isset($line)) ? ":$line" : '') . " : $message" . CR_LF;
        fwrite($handle, $message . CR_LF);
        fclose($handle);
    }

    public static function exception($ex, $filename = null, $line = null)
    {
        $message = '';

        $message .= $ex->getCode() . CR_LF;
        $message .= $ex->getFile() . CR_LF;
        $message .= $ex->getMessage() . CR_LF;
        $message .= $ex->getTraceAsString() . CR_LF;

        self::debug($message, $filename, $line);
    }
    
}

function debugLog(String $message, $filename = '', $line = 0)
    {
    TLog::debug($message, $filename, $line);
}

function exceptionLog(Exception $ex, $filename = '', $line = 0)
    {
    TLog::exception($ex, $filename, $line);
}
