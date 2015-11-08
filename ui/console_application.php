<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Phoenix\UI;

//require_once 'application.php';

use Phoenix\Core\TApplication;

class TConsoleApplication extends TApplication
{
    //put your code here
    
    public function init() {}

    public function run() {}

    public function __construct($argv = array())
    {
        if($argv == null) $argv = array();
        $this->_argv = $argv;
        $useTransaction = $this->getArgument('ut', 'useTransactions');
        $execution = $this->getArgument('em', 'executionMode');
        $verbose = $this->getArgument('v', 'verbose');
        TApplication::setExecutionMode($execution);
        TApplication::useTransactions($useTransaction);
        
        $this->init();
        $this->run();
    }
    
    public function printUsage()
    {
        $this->log($this->getOS());
        $this->log('Paramètres attendus : ');
        foreach ($this->_parameters as $parameter) {
            $this->log(' -' . $parameter['short'] . (($parameter['long'] != '') ? ' ou --' . $parameter['long'] : ''));
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
