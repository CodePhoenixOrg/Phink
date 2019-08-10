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

/**
 * Description of Application
 *
 * @author david
 */

use Phink\Core\TObject;
use Phink\Cache\TCache;
use Phink\Auth\TAuthentication;
use Phink\Registry\TRegistry;
use Phink\Registry\TRegistry as PhinkTRegistry;

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
    protected $appTitle = '';
    protected $scriptName = 'app.php';
    protected $appDirectory = '';
    protected $canStop = false;
    protected $dataConfName = '';
    private $_usage = '';
    private $_appini = [];

    public function __construct()
    {
        parent::__construct();
    }

    protected function execute(): void
    { }

    protected function ignite(): void
    {
        $this->loadINI();

        $this->setCommand(
            'help',
            'h',
            'Display this help',
            function (callable $callback = null) {
                $this->help();
                $data = $this->_usage;
                if ($callback !== null) {
                    \call_user_func($callback, $data);
                }
            }
        );

        $this->setCommand(
            'ini',
            '',
            'Display the ini file if exists',
            function (callable $callback = null) {
                $this->loadINI();
                $data = $this->_appini;
                $this->writeLine($data);
                if ($callback !== null) {
                    \call_user_func($callback, $data);
                }
            }
        );

        $this->setCommand(
            'os',
            '',
            'Display the running operating system name.',
            function (callable $callback = null) {
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
            function (callable $callback = null) {
                $data = $this->getName();
                $this->writeLine($data);
                if ($callback !== null) {
                    \call_user_func($callback, $data);
                }
            }
        );

        $this->setCommand(
            'title',
            '',
            'Display the running application title.',
            function (callable $callback = null) {
                $data = $this->getTitle();
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

        $this->setCommand(
            'debug',
            '',
            'Display the debug log.',
            function (callable $callback = null) {
                $data = $this->getDebugLog();
                if ($callback !== null) {
                    \call_user_func($callback, $data);
                }
            }
        );

        $this->setCommand(
            'info-modules',
            '',
            'Display the module section of phpinfo() output.',
            function (callable $callback = null) {
                ob_start();
                phpinfo(INFO_MODULES);
                $data = ob_get_clean();
                if ($callback !== null) {
                    \call_user_func($callback, $data);
                }
            }
        );

        $this->setCommand(
            'error',
            '',
            'Display the php error log.',
            function (callable $callback = null) {
                $data = $this->getPhpErrorLog();
                if ($callback !== null) {
                    \call_user_func($callback, $data);
                }
            }
        );

        $this->setCommand(
            'rlog',
            '',
            'All log files cleared',
            function (callable $callback = null) {
                $data = $this->clearLogs();
                if ($callback !== null) {
                    \call_user_func($callback, $data);
                }
            }
        );
    }

    abstract protected function displayConstants(): array;

    public function clearLogs(): string
    {
        $result = '';
        try {
            self::getLogger()->clearAll();

            $result = 'All logs cleared';
        } catch (\Throwable $ex) {
            self::writeException($ex);

            $result = 'Impossible to clear logs';
        }
        return $result;
    }

    public function clearRuntime(): string
    {
        $result = '';
        try {
            TCache::clearRuntime();

            $result = 'All runtime files deleted';
        } catch (\Throwable $ex) {
            self::writeException($ex);

            $result = 'Impossible to delete runtime files';
        }
        return $result;
    }

    public function getDebugLog(): string
    {
        return self::getLogger()->getDebugLog();
    }

    public function getPhpErrorLog(): string
    {
        return self::getLogger()->getPhpErrorLog();
    }

    public function loadINI(): void
    {
        try {
            $ini = null;
            if (!file_exists(SRC_ROOT . 'config/app.ini')) {
                return;
            }
            $ini = parse_ini_file(SRC_ROOT  . 'config/app.ini', TRUE, INI_SCANNER_TYPED);
            $this->appName = isset($ini['application']['name']) ? $ini['application']['name'] : $this->appName;
            $this->appTitle = isset($ini['application']['title']) ? $ini['application']['title'] : $this->appTitle;
            
            foreach($ini as $key=>$values) {
                TRegistry::write('ini', $key, $values);
            }
            unset($ini);
            // $this->dataConfName = isset($ini['config']) ? $ini['config'] : $this->dataConfName;

            $dataDir = dir(realpath(APP_DATA));

            $entry = '';
            while (($entry = $dataDir->read()) !== false) {
                $info = (object) \pathinfo($entry);

                if ($info->extension == 'json') {
                    $conf = file_get_contents(APP_DATA . $entry);
                    $conf = json_decode($conf, true);
                    TRegistry::write('connections', $info->filename, $conf);
                    // self::getLogger()->dump('DATA CONF ' . $info->filename, $conf);
                }
            }
            $dataDir->close();

            // if (file_exists(APP_DATA . $this->dataConfName)) {
            //     $confs = file_get_contents(APP_DATA . $this->dataConfName);
            //     $confs = $confs->connections;
            //     TRegistry::write('data', 'connections', $confs);
            // }
            $this->_appini = TRegistry::item('ini');
        } catch (\Throwable $ex) {
            $this->writeException($ex);
        }
    }

    public static function write($string, ...$params): void
    { }

    public static function writeLine($string, ...$params): void
    { }

    public static function writeException(\Throwable $ex, $file = null, $line = null): void
    { }

    public function help(): void
    {
        $this->writeLine($this->getName());
        $this->writeLine('Expected commands : ');
        $this->writeLine($this->_usage);
    }

    public function getName(): string
    {
        return $this->appName;
    }
    
    public function getTitle(): string
    {
        return $this->appTitle;
    }

    public function getDirectory(): string
    {
        return $this->appDirectory;
    }

    public function setCommand(string $long, string $short = '', string $definition = '', $callback = null): void
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

    public static function getExecutionMode(): string
    {
        return self::$_executionMode;
    }

    public function getOS(): string
    {
        return PHP_OS;
    }

    public static function setExecutionMode($myExecutionMode): void
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

    public static function getVerboseMode(): bool
    {
        return self::$_verboseMode;
    }

    public static function setVerboseMode($set = false)
    {
        self::$_verboseMode = $set;
    }

    public static function getTransactionUse(): bool
    {
        return self::$_useTransactions;
    }

    public static function useTransactions($set = true): void
    {
        self::$_useTransactions = $set;
    }

    public static function isProd(): bool
    {
        return self::$_executionMode == self::PROD_MODE;
    }

    public static function isTest(): bool
    {
        return self::$_executionMode == self::TEST_MODE;
    }

    public static function isDebug(): bool
    {
        return self::$_executionMode == self::DEBUG_MODE;
    }

    public static function authenticateByToken($token): string
    {

        // On prend le token en cours
        if (is_string($token)) {
            // avec ce token on récupère l'utilisateur et un nouveau token
            $token = TAuthentication::getUserCredentialsByToken($token);
        }

        return $token;
    }

    protected static function _write($string, ...$params): string
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
