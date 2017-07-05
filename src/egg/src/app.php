<?php

/* 
 * Copyright (C) 2017 dpjb
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

$filename = __DIR__ . '/../../phink_library.php';
if(Phar::running() != '') {
    $filename =  'phink_library.php';
}
include $filename;

include 'lib.php';

class Egg extends Phink\Core\TApplication {

    /**
     * Defines the full directory tree of a Phink web application
     */

    /**
     * Application starter
     * 
     * @param array $argv List of argunments of the command line
     * @param int $argc Count the number of these arguments
     */
    public static function main($args_v, $args_c = 0) {
        
        (new Egg($args_v, $args_c));
        
    }

    /**
     * Takes arguments of the command line in parameters.
     * The start make this job fine.
     * 
     * @param array $argv List of argunments of the command line
     * @param int $argc Count the number of these arguments
     */
    public function __construct($args_v, $args_c = 0)
    {
        $dir = dirname(__FILE__);
        parent::__construct($args_v, $args_c, $dir);
        
        try {
            $egg = new EggLib();
            
//            if(!class_exists('\Phink\TAutoloader')) {
//                $this->_requirePhink();
//            }
//            
            
            if ($this->getArgument('create')) {
                $egg->create();
            }

            if ($this->getArgument('delete')) {
                $egg->delete();
            }

        } catch (\Exception $ex) {
            echo $ex->getMessage() . PHP_EOL;
        }
    }

   
}
 
Egg::main($argv, $argc);
