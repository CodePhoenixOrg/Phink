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

    protected $parameters = [];
    protected $callbacks = [];
    protected $appName = 'app';
    protected $scriptName = 'app.php';
    protected $appDirectory = '';
    protected $canStop = false;
    private $_usage = '';

    private $redis = null;

    protected function ignite()
    {
        $path = explode(DIRECTORY_SEPARATOR, $this->appDirectory);
        $scriptDir = $this->appDirectory . '..' . DIRECTORY_SEPARATOR;
        $siteDir = $scriptDir . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
        $siteDir = \Phink\Utils\TFileUtils::relativePathToAbsolute($siteDir);
        $scriptDir = \Phink\Utils\TFileUtils::relativePathToAbsolute($scriptDir);
        
        define('SITE_ROOT', $siteDir);
        define('SCRIPT_ROOT', $scriptDir);
        
        array_pop($path);
        if (APP_IS_PHAR) {
            array_pop($path);
            $this->appDirectory = str_replace('phar://', '', $scriptDir);
        }
        
        define('APP_ROOT', SITE_ROOT . 'app' . DIRECTORY_SEPARATOR);
        define('APP_SCRIPTS', APP_ROOT . 'scripts' . DIRECTORY_SEPARATOR);
        define('APP_DATA', SITE_ROOT . 'data' . DIRECTORY_SEPARATOR);
        define('APP_BUSINESS', APP_ROOT . 'business' . DIRECTORY_SEPARATOR);
        define('CONTROLLER_ROOT', APP_ROOT . 'controllers' . DIRECTORY_SEPARATOR);
        define('BUSINESS_ROOT', APP_ROOT . 'business' . DIRECTORY_SEPARATOR);
        define('MODEL_ROOT', APP_ROOT . 'models' . DIRECTORY_SEPARATOR);
        define('REST_ROOT', APP_ROOT . 'rest' . DIRECTORY_SEPARATOR);
        define('VIEW_ROOT', APP_ROOT . 'views' . DIRECTORY_SEPARATOR);
        define('CACHE_DIR', SITE_ROOT . 'cache' . DIRECTORY_SEPARATOR);
    
        array_pop($path);
        $this->appName = array_pop($path);
        
        $this->setParameter(
            'help',
            'h',
            'Display this help',
            function () {
                $this->help();
            }
        );
        
        $this->setParameter(
            'os',
            '',
            'Display the running operating system name.',
            function () {
                \Phink\UI\TConsoleApplication::writeLine($this->getOS());
            }
        );
        
        $this->setParameter(
            'name',
            '',
            'Display the running application name.',
            function () {
                \Phink\UI\TConsoleApplication::writeLine($this->getApplicationName());
            }
        );
    
        $this->setParameter(
            'source-path',
            '',
            'Display the running application source directory.',
            function () {
                \Phink\UI\TConsoleApplication::writeLine($this->getApplicationDirectory());
            }
        );

        $this->setParameter(
            'script-path',
            '',
            'Display the running application root.',
            function () {
                \Phink\UI\TConsoleApplication::writeLine(SCRIPT_ROOT);
            }
        );
    }
    
    public function help()
    {
        \Phink\UI\TConsoleApplication::writeLine($this->getApplicationName());
        \Phink\UI\TConsoleApplication::writeLine('Expected parameters : ');
        \Phink\UI\TConsoleApplication::writeLine($this->_usage);
    }

    public function getApplicationName()
    {
        return $this->appName;
    }

    public function getApplicationDirectory()
    {
        return $this->appDirectory;
    }

    public function setParameter(string $long, string $short = '', string $definition = '', $callback = null)
    {
        $this->parameters[$long] = [
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
}
