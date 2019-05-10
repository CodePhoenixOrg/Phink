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
 

namespace Phink\Core;

//$single_server = array(
//    'host' => '192.168.1.8',
//    'port' => 6379,
//    'database' => 15
//);

include 'constants.php';

/**
 * Description of Application
 *
 * @author david
 */

use Phink\Core\TObject;
use Phink\MVC\TView;
use Phink\Auth\TAuthentication;

abstract class TCustomApplication extends TObject
{
    //put your code here
    const DEBUG_MODE = 'DEBUG';
    const TEST_MODE = 'TEST';
    const PROD_MODE = 'PROD';
    
    private static $_executionMode = self::PROD_MODE;
    private static $_verboseMode = false;
    private static $_useTransactions = true;

    protected $commands = [];
    protected $callbacks = [];
    protected $appName = 'app';
    protected $scriptName = 'app.php';
    protected $appDirectory = '';
    protected $canStop = false;
    private $_usage = '';

    private $redis = null;

    public function __construct()
    {
        parent::__construct();
    }

    protected function execute()
    {
    }

    protected function ignite()
    {
        $this->loadINI();

        $this->setCommand(
            'help',
            'h',
            'Display this help',
            function ($callback = null) {
                $this->help();
                $data = $this->_usage;
                if ($callback !== null) {
                    \call_user_func($callback, $data);
                }
            }
        );
        
        $this->setCommand(
            'os',
            '',
            'Display the running operating system name.',
            function ($callback = null) {
                $data = $this->getOS();
                $this->writeLine($data);
                if ($callback !== null) {
                    \call_user_func($callback, $data);
                }
            }
        );
        
        $this->setCommand(
            'name',
            '',
            'Display the running application name.',
            function ($callback = null) {
                $data = $this->getApplicationName();
                $this->writeLine($data);
                if ($callback !== null) {
                    \call_user_func($callback, $data);
                }
            }
        );
    
        $this->setCommand(
            'constants',
            '',
            'Display the application constants.',
            function (callable $callback = null) {
                $data = $this->displayConstants();
                if ($callback !== null) {
                    \call_user_func($callback, $data);
                }
            }
        );
    }
    
    protected function displayConstants() : array
    {
    }

    public function loadINI() : void
    {
        try {
            $ini = null;
            if (!file_exists(SITE_ROOT . 'config/app.ini')) {
                return;
            }
            $ini = parse_ini_file(SITE_ROOT  . 'config/app.ini');
            $data = isset($ini['data']) ?? $ini['data'];

            self::getLogger()->dump('INI_DATA:', $ini);
        } catch (\Throwable $ex) {
            $this->writeException($ex);
        }
    }

    public static function write($string, ...$params)
    {
    }
        
    public static function writeLine($string, ...$params)
    {
    }

    public static function writeException(\Throwable $ex, $file = null, $line = null)
    {
    }

    public function help()
    {
        $this->writeLine($this->getApplicationName());
        $this->writeLine('Expected commands : ');
        $this->writeLine($this->_usage);
    }

    public function getApplicationName()
    {
        return $this->appName;
    }

    public function getApplicationDirectory()
    {
        return $this->appDirectory;
    }

    public function setCommand(string $long, string $short = '', string $definition = '', $callback = null)
    {
        $this->commands[$long] = [
            'short' => $short,
            'definition' => $definition,
            'callback' => $callback
        ];
        
        if ($short !== '') {
            $this->_usage .= "\t--$long, -$short : $definition" . PHP_EOL;
        } else {
            $this->_usage .= "\t--$long : $definition" . PHP_EOL;
        }
    }
    
    // public function commandRunner(string $cmd, callable $callback, $arg = null) {

    //     if (isset($this->commands[$cmd])) {
    //         $cmd = $this->commands[$cmd];
    //         $statement = $cmd['callback'];

    //         if ($statement !== null && $arg === null) {
    //             call_user_func($statement, $callback);
    //         } elseif ($statement !== null && $arg !== null) {
    //             call_user_func($statement, $callback, $arg);
    //         }

    //         return TRegistry::read('console', 'buffer');
    //     }
    // }

    public function canStop()
    {
        return $this->canStop;
    }

    public static function getExecutionMode()
    {
        return self::$_executionMode;
    }
    
    public function getOS()
    {
        return PHP_OS;
    }

    public static function setExecutionMode($myExecutionMode)
    {
        if (!$myExecutionMode) {
            $myExecutionMode = (APP_IS_WEB) ?  'debug' : 'prod';
        }
        
        $prod = ($myExecutionMode == 'prod');
        $test = ($myExecutionMode == 'test' || $myExecutionMode == 'devel' || $myExecutionMode == 'dev');
        $debug = ($myExecutionMode == 'debug');
        
        if ($prod) {
            self::$_executionMode = self::PROD_MODE;
        }
        if ($test) {
            self::$_executionMode = self::TEST_MODE;
        }
        if ($debug) {
            self::$_executionMode = self::DEBUG_MODE;
        }
    }
    
    public function setRedis(array $params)
    {
        $this->redis = $params;
    }

    public function getRedis()
    {
        return $this->redis;
    }

    public static function getVerboseMode()
    {
        return self::$_verboseMode;
    }
    
    public static function setVerboseMode($set = false)
    {
        self::$_verboseMode = $set;
    }
    
    public static function getTransactionUse()
    {
        return self::$_useTransactions;
    }

    public static function useTransactions($set = true)
    {
        self::$_useTransactions = $set;
    }
    
    public static function isProd()
    {
        return self::$_executionMode == self::PROD_MODE;
    }

    public static function isTest()
    {
        return self::$_executionMode == self::TEST_MODE;
    }

    public static function isDebug()
    {
        return self::$_executionMode == self::DEBUG_MODE;
    }

    public static function authenticateByToken($token)
    {
        
        // On prend le token en cours
        if (is_string($token)) {
            // avec ce token on récupère l'utilisateur et un nouveau token
            $token = TAuthentication::getUserCredentialsByToken($token);
        }
        
        return $token;
    }

    protected static function _write($string, ...$params)
    {
        if (is_array($string)) {
            $string = print_r($string, true);
        }
        $result = $string;
        if (count($params) > 0 && is_array($params[0])) {
            $result = vsprintf($string, $params[0]);
        }
        return $result;
    }
}
