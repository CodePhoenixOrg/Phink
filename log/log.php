<?php

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
        if(DOCUMENT_ROOT == '') {
            self::$_debugLogFile = './logs/debug.log';
        } else {
            self::$_debugLogFile = DOCUMENT_ROOT . 'logs/debug.log';
        }
    }

    public static function dump($message, $object)
    {
        self::debug($message . ' : ' . print_r($object, true));
    }
    
    public static function debug($message, $filename = null, $line = null)
    {
        self::_setDebugLogFile();
        $handle = fopen(self::$_debugLogFile, 'a');

        if(DOCUMENT_ROOT) {
            $filename = substr($filename, strlen(DOCUMENT_ROOT));
        }
        $message = date('Y-m-d h:i:s') . ((isset($filename)) ? ":$filename" : '') . ((isset($line)) ? ":$line" : '') . " : $message" . CR_LF;
        fwrite($handle, $message);
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
