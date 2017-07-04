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

namespace Phink\UI;

//require_once 'application.php';

use Phink\Core\TApplication;

class TConsoleApplication extends TApplication
{
    //put your code here
    
    public function init() {}

    public function run() {}

    public function __construct($argv = array())
    {
        if($argv == null) $argv = array();
        $this->_argv = $argv;
        $useTransaction = $this->getArgument('useTransactions');
        $execution = $this->getArgument('executionMode');
        $verbose = $this->getArgument('verbose');
        TApplication::setExecutionMode($execution);
        TApplication::useTransactions($useTransaction);
        
        $this->init();
        $this->run();
    }
    
    public function printUsage()
    {
        $this->log($this->getOS());
        $this->log('Expected parameters : ');
        foreach ($this->_parameters as $parameter) {
            $this->log(' -' . $parameter['short'] . (($parameter['long'] != '') ? ' or --' . $parameter['long'] : ''));
        }
    }
    
    public static function write($string = '', $params = null)
    {
        if($params != null) {
            printf($string, $params);
        } else {
            print $string;
        }
    }
    
    public static function writeLine($string = '', $params = null)
    {
        if($params != null) {
            printf($string, $params) . CR_LF;
        } else {
            print $string . CR_LF;
        }
    }

    
}
