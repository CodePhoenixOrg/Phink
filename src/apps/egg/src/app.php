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
namespace Phink\Apps\Egg;

use Phink\Data\TDataAccess;

include (\Phar::running() !== '') ? 'phink_library.php' : '../../../phink/phink_library.php';
include 'lib.php';

class App extends \Phink\UI\TConsoleApplication implements \Phink\UI\IPhar {

    /**
     * Application starter
     * 
     * @param array $argv List of argunments of the command line
     * @param int $argc Count the number of these arguments
     */
    public static function main($args_v, $args_c) {
        (new App($args_v, $args_c));
    }

    /**
     * Takes arguments of the command line in parameters.
     * The start make this job fine.
     * 
     * @param array $argv List of argunments of the command line
     * @param int $argc Count the number of these arguments
     */
    public function __construct($args_v, $args_c)
    {
        $dir = dirname(__FILE__);
        parent::__construct($args_v, $args_c, $dir);
    }
    
    /**
     * Entrypoint of a TConsoleApplication
     */
    protected function ignite() : void
    {
        parent::ignite();

        try {
            $egg = new EggLib($this);

            $this->setCommand(
                'create',
                '',
                'Create the application tree.',
                function () use ($egg) {
                    $egg->createTree();
                }
            );            

            $this->setCommand(
                'delete',
                '',
                'Delete the application tree.',
                function () use($egg) {
                    $egg->deleteTree();
                }
            ); 

            $this->setCommand(
                'make-scripts',
                '',
                'Make scripts based on configuration file and table name passed as argument.',
                function (string $usertable) use($egg) {
                    $egg->makeScripts($usertable);
                }
            ); 

        } catch(\Throwable $th) {
            self::writeException($th);
        }
    }

    // public function addPharFiles()
    // {
    //     $this->addFileToPhar(SITE_ROOT . "app.php", "app.php");
    //     $this->addFileToPhar(SITE_ROOT . "lib.php", "lib.php");
     
    // }
   
}
 
App::main($argv, $argc);
