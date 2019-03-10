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
 
namespace Phink\UI;

class TConsoleApplication extends \Phink\Core\TApplication
{
    //put your code here
    
    public function init() {}

    public function run() {}

    public function __construct($arg_v = [], $arg_c = 0, $app_dir = '')
    {
        parent::__construct($arg_v, $arg_c, $app_dir);
        parent::ignite();
    
        $useTransaction = $this->getArgument('useTransactions');
        $execution = $this->getArgument('executionMode');
        $verbose = $this->getArgument('verbose');
        self::setExecutionMode($execution);
        self::useTransactions($useTransaction);
        
        $this->init();
        $this->run();
    }
    
    public function printUsage()
    {
        $this->getLogger()->debug($this->getOS());
        $this->getLogger()->debug('Expected parameters : ');
        foreach ($this->_parameters as $parameter) {
            $this->getLogger()->debug(' -' . $parameter['short'] . (($parameter['long'] != '') ? ' or --' . $parameter['long'] : ''));
        }
    }
    
    private static function _write($string, ...$params)
    {
        if(is_array($string)) {
            $string = print_r($string, true);
        }
        $result = $string;
        if(count($params) > 0 && is_array($params[0])) {
            $result = vsprintf($string, $params[0]);
        }
        return $result;
        
    }


    public static function write($string, ...$params)
    {
        $result = self::_write($string, $params);
        if(!APP_IS_WEB) {
            print $result;
        } else {
            self::getLogger()->debug($result);
        }
    }
    
    public static function writeLine($string, ...$params)
    {
        $result = self::_write($string, $params);
        if(!APP_IS_WEB) {
            print $result . PHP_EOL;
        } else {
            self::getLogger()->debug($result . PHP_EOL);
        }
    }

    public static function writeException($ex, $file = null, $line = null)
    {
        if(!APP_IS_WEB) {
            $message = '';

            $message .= 'Error code: ' . $ex->getCode() . PHP_EOL;
            $message .= 'In ' . $ex->getFile() . ', line ' . $ex->getLine() . PHP_EOL;
            $message .= 'With the message: ' . $ex->getMessage() . PHP_EOL;
            $message .= 'Stack trace: ' . $ex->getTraceAsString() . PHP_EOL;
            
            print  "\e[41m\033[37m" . $message . "\e[0m\033[0m";
        } else {
            self::getLogger()->exception($ex, $file, $line);
        }
    }

    public static function writeThrowable(\Throwable $th, $file = null, $line = null)
    {
        if(!APP_IS_WEB) {
            $message = '';

            $message .= 'Error code: ' . $th->getCode() . PHP_EOL;
            $message .= 'In ' . $th->getFile() . ', line ' . $th->getLine() . PHP_EOL;
//            $message .= $th->getMessage() . PHP_EOL;
//            $message .= $th->getTraceAsString() . PHP_EOL;
            
            print  "\e[41m\033[37m" . $message . "\e[0m\033[0m";
        } else {
            self::getLogger()->exception($th, $file, $line);
        }
    }
    
}
