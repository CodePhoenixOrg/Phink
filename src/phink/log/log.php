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
    
    private $_debugLogFile = '';

    private function _setDebugLogFile()
    {
        $this->_debugLogFile = './logs/debug.log';
        if(APP_IS_WEB) {
            $this->_debugLogFile = SITE_ROOT . 'logs/debug.log';
        }
    }

    public function file($filename, $object)
    {
        file_put_contents (DOCUMENT_ROOT . RUNTIME_DIR . $filename . '.log', print_r($object, true) . PHP_EOL);
    }
    
    public function dump($message, $object)
    {
        $this->debug($message . '::' . print_r($object, true) . PHP_EOL);
    }

    public function debug($message, $filename = null, $line = null)
    {
        $message = (is_array($message) || is_object($message)) ? print_r($message, true) : $message;
        $this->_setDebugLogFile();
        
        if(!file_exists('logs')) {
            mkdir('logs', 0755);
        }
        $handle = fopen($this->_debugLogFile, 'a');

        if(SITE_ROOT) {
            $filename = substr($filename, strlen(SITE_ROOT));
        }
        $message = date('Y-m-d h:i:s') . ((isset($filename)) ? ":$filename" : '') . ((isset($line)) ? ":$line" : '') . " : $message" . PHP_EOL;
        fwrite($handle, $message . PHP_EOL);
        fclose($handle);
    }

    public function exception($ex, $filename = null, $line = null)
    {
        $message = '';

        $message .= $ex->getCode() . PHP_EOL;
        $message .= $ex->getFile() . PHP_EOL;
        $message .= $ex->getMessage() . PHP_EOL;
        $message .= $ex->getTraceAsString() . PHP_EOL;

        $this->debug($message, $filename, $line);
    }
    
}
