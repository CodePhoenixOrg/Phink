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
        if(APP_ROOT == '') {
            $this->_debugLogFile = './logs/debug.log';
        } else {
            $this->_debugLogFile = APP_ROOT . 'logs/debug.log';
        }
    }

    public function file($filename, $object)
    {
        file_put_contents (DOCUMENT_ROOT . RUNTIME_DIR . $filename . '.log', print_r($object, true) . CR_LF);
    }
    
    public function dump($message, $object)
    {
        $this->debug($message . '::' . print_r($object, true) . CR_LF);
    }

    public function debug($message, $filename = null, $line = null)
    {
        $message = (is_array($message)) ? print_r($message, true) : $message;
        $this->_setDebugLogFile();
        $handle = fopen($this->_debugLogFile, 'a');

        if(APP_ROOT) {
            $filename = substr($filename, strlen(APP_ROOT));
        }
        $message = date('Y-m-d h:i:s') . ((isset($filename)) ? ":$filename" : '') . ((isset($line)) ? ":$line" : '') . " : $message" . CR_LF;
        fwrite($handle, $message . CR_LF);
        fclose($handle);
    }

    public function exception($ex, $filename = null, $line = null)
    {
        $message = '';

        $message .= $ex->getCode() . CR_LF;
        $message .= $ex->getFile() . CR_LF;
        $message .= $ex->getMessage() . CR_LF;
        $message .= $ex->getTraceAsString() . CR_LF;

        $this->debug($message, $filename, $line);
    }
    
}
