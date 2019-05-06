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

class TConsoleApplication extends \Phink\Core\TCustomApplication
{
    //put your code here
    protected $_argv;
    protected $_argc;
    private $_phar = null;

    public function init()
    {
    }

    public function run()
    {
    }

    public function __construct(array $argv = [], int $argc = 0, string $appDirectory = '.')
    {
        //    if(!class_exists('\Phink\TAutoloader')) {
        //        include 'phink/autoloader.php';
        //        \Phink\TAutoLoader::register();
        //    }
        parent::__construct();

        $this->_argv = $argv;
        $this->_argc = $argc;

        if (\Phar::running() !== '') {
            $this->appName = $argv[0];
        }

        $this->scriptName = $argv[0];

        $this->appDirectory = $appDirectory . DIRECTORY_SEPARATOR;
    
        $path = explode(DIRECTORY_SEPARATOR, $this->appDirectory);
        $scriptDir = $this->appDirectory . '..' . DIRECTORY_SEPARATOR;
        $siteDir = $scriptDir . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
        $siteDir = \Phink\Utils\TFileUtils::relativePathToAbsolute($siteDir);
        $scriptDir = \Phink\Utils\TFileUtils::relativePathToAbsolute($scriptDir);
        
        define('SITE_ROOT', $siteDir);
        define('SCRIPT_ROOT', $scriptDir);

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
        if (APP_IS_PHAR) {
            array_pop($path);
            $this->appDirectory = str_replace('phar://', '', $scriptDir);
        }

        array_pop($path);
        $this->appName = array_pop($path);

        $useTransaction = $this->setCommand('useTransactions');
        $execution = $this->setCommand('executionMode');
        $verbose = $this->setCommand('verbose');

        self::setExecutionMode($execution);
        self::useTransactions($useTransaction);

        $this->ignite();
        $this->execute();

        $this->init();
        $this->run();
    }

    protected function ignite()
    {
        parent::ignite();
        
        if (!APP_IS_PHAR) {
            $this->setCommand(
                'make-phar',
                '',
                'Make a phar archive of the current application.',
                function () {
                    $this->makePhar();
                }
            );
            $this->setCommand(
                'require-master',
                '',
                'Download the ZIP file of the master branch of Phink framework.',
                function () {
                    $this->_requireMaster();
                }
            );
        }
        
        $this->setCommand(
            'source-path',
            '',
            'Display the running application source directory.',
            function () {
                \Phink\UI\TConsoleApplication::writeLine($this->getApplicationDirectory());
            }
        );

        $this->setCommand(
            'script-path',
            '',
            'Display the running application root.',
            function () {
                \Phink\UI\TConsoleApplication::writeLine(SCRIPT_ROOT);
            }
        );
        $this->setCommand(
            'display-tree',
            '',
            'Display the tree of the current application.',
            function () {
                $this->displayTree($this->appDirectory);
            }
        );

        $this->setCommand(
            'display-master-tree',
            '',
            'Display the tree of the master branch of Phink framework previously downloaded.',
            function () {
                try {
                    $this->displayTree('master' . DIRECTORY_SEPARATOR . 'Phink-master' . DIRECTORY_SEPARATOR. 'src' . DIRECTORY_SEPARATOR . 'phink');
                } catch (\Throwable $ex) {
                    \Phink\UI\TConsoleApplication::writeException($ex);
                } catch (\Exception $ex) {
                    \Phink\UI\TConsoleApplication::writeException($ex);
                }
            }
        );
    }
    
    protected function execute()
    {
        $isFound = false;
        $result = null;

        foreach ($this->commands as $long => $cmd) {
            $short = $cmd['short'];
            $callback = $cmd['callback'];
            for ($i = 1; $i < $this->_argc; $i++) {
                if ($this->_argv[$i] == '--' . $long) {
                    $isFound = true;

                    if (isset($this->_argv[$i+1])) {
                        if (substr($this->_argv[$i+1], 0, 1) !== '-') {
                            $result = $this->_argv[$i + 1];
                        }
                    }

                    break;
                } elseif ($this->_argv[$i] == '-' . $short) {
                    $isFound = true;

                    $sa = explode('=', $this->_argv[$i]);
                    if (count($sa) > 1) {
                        $result = $sa[1];
                    }

                    break;
                }
            }
            if ($isFound) {
                break;
            }
        }

        if ($callback !== null && $isFound && $result === null) {
            call_user_func($callback);
        } elseif ($callback !== null && $isFound && $result !== null) {
            call_user_func($callback, $result);
        }
    }

    protected function displayConstants()
    {
        try {
            $constants = [];
            $constants['SITE_ROOT'] = SITE_ROOT;
            $constants['SCRIPT_ROOT'] = SCRIPT_ROOT;
            $constants['APP_ROOT'] = APP_ROOT;
            $constants['APP_DATA'] = APP_DATA;
            $constants['APP_BUSINESS'] = APP_BUSINESS;
            $constants['APP_SCRIPTS'] = APP_SCRIPTS;
            $constants['CONTROLLER_ROOT'] = CONTROLLER_ROOT;
            $constants['MODEL_ROOT'] = MODEL_ROOT;
            $constants['REST_ROOT'] = REST_ROOT;
            $constants['VIEW_ROOT'] = VIEW_ROOT;
            $constants['LOG_PATH'] = LOG_PATH;
            $constants['DEBUG_LOG'] = DEBUG_LOG;
            $constants['ERROR_LOG'] = ERROR_LOG;
            $constants['CACHE_DIR'] = CACHE_DIR;
        
            \Phink\UI\TConsoleApplication::writeLine('Application constants are :');
            foreach($constants as $key => $value) {
                \Phink\UI\TConsoleApplication::writeLine("\033[0m\033[0;36m" . $key . "\033[0m\033[0;33m" . ' => ' . "\033[0m\033[0;34m" . $value . "\033[0m\033[0m");
            }
        } catch (\Throwable $ex) {
            self::writeException($ex);
        }
    }

    private static function _requireMaster(string $path = '')
    {
        $result = [];
        $master = $path . 'master';
        $filename = $master . '.zip';
        $phinkDir = $master . DIRECTORY_SEPARATOR . 'Phink-master' . DIRECTORY_SEPARATOR. 'src' . DIRECTORY_SEPARATOR . 'phink';
        $tree = [];

        if (!file_exists($filename)) {
            self::writeLine('Downloading Phink github master');
            $curl = new \Phink\Web\TCurl();
            $result = $curl->request('https://codeload.github.com/CodePhoenixOrg/Phink/zip/master');
            file_put_contents($filename, $result->content);
        }

        if (file_exists($filename)) {
            self::writeLine('Inflating Phink master archive');
            $zip = new \Phink\Utils\TZip();
            $zip->deflat($filename);
        }
        
        if (file_exists($master)) {
            //$phinkDir = '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'phink';
            $tree = \Phink\TAutoloader::walkTree($phinkDir, ['php']);
        }
        
        $result = ['path' => $phinkDir, 'tree' => $tree];
        
        return (object)$result;
    }
    
    public function addFileToPhar($file, $name)
    {
        self::writeLine("Adding %s as %s", $file, $name);
        $this->_phar->addFile($file, $name);
    }
    
    public function makePhar()
    {
        try {

            // if (APP_IS_WEB) {
            //     throw new \Exception('Still cannot make a phar of a web application!');
            // }
            ini_set('phar.readonly', 0);
        
            // the current directory must be src
            $srcRoot = $this->appDirectory;
            $buildRoot = $srcRoot . '..' . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR;
            $libRoot = $srcRoot . '..' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR;
            $pharName = $this->appName . ".phar";
        
            if (file_exists($buildRoot . $pharName)) {
                unlink($buildRoot . $pharName);
            }

            if (!file_exists($buildRoot)) {
                mkdir($buildRoot);
            }

            if (!file_exists($libRoot)) {
                mkdir($libRoot);
            }

            $this->_phar = new \Phar(
                $buildRoot . $pharName,
                \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::KEY_AS_FILENAME,
                $pharName
            );
        
            // start buffering. Mandatory to modify stub.
            $this->_phar->startBuffering();
        
            // Get the default stub. You can create your own if you have specific needs
            $defaultStub = $this->_phar->createDefaultStub("app.php");
        
            self::writeLine('APP_DIR::' . $this->appDirectory);
            $this->addPharFiles();

            $master = self::_requireMaster($libRoot);
            $phinkDir = $libRoot .'master' . DIRECTORY_SEPARATOR . 'Phink-master' . DIRECTORY_SEPARATOR. 'src' . DIRECTORY_SEPARATOR . 'phink' . DIRECTORY_SEPARATOR;
            $phink_builder = $phinkDir . 'phink_library.php';

            $phink_builder = \Phink\Utils\TFileUtils::relativePathToAbsolute($phink_builder);
            $this->addFileToPhar($phink_builder, "phink_library.php");
        
            foreach ($master->tree as $file) {
                $filename = $master->path . $file;
                $filename = \Phink\Utils\TFileUtils::relativePathToAbsolute($filename);
                $info = pathinfo($file, PATHINFO_BASENAME);
            
                $this->addFileToPhar($filename, $info);
            }

            // Create a custom stub to add the shebang
            $execHeader = "#!/usr/bin/env php \n";
            if (PHP_OS == 'WINNT') {
                $execHeader = "@echo off\r\nphp.exe\r\n";
            }
            $stub = $execHeader . $defaultStub;

            // Add the stub
            $this->_phar->setStub($stub);

            $this->_phar->stopBuffering();

            $buildRoot = $this->appDirectory . '..' . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR;
            $execname = $buildRoot . $this->_name;
            if (PHP_OS == 'WINNT') {
                $execname .= '.bat';
            }

            rename($buildRoot . $this->_name . '.phar', $execname);
        } catch (\Throwable $ex) {
            self::writeException($ex);
        }
    }
    
    public function addPharFiles()
    {
        try {
            self::writeLine('APP_DIR_2::' . $this->appDirectory . $this->scriptName);
            $tree = \Phink\TAutoloader::walkTree($this->appDirectory, ['php']);
    
            if (isset($tree[$this->appDirectory . $this->scriptName])) {
                unset($tree[$this->appDirectory . $this->scriptName]);
                $this->addFileToPhar($this->appDirectory . $this->scriptName, $this->scriptName);
            }
            foreach ($tree as $filename) {
                $this->addFileToPhar($this->appDirectory . $filename, $filename);
            }
        } catch (\Throwable $ex) {
            self::writeException($ex);
        }
    }

    public function displayTree($path)
    {
        $tree = \Phink\TAutoloader::walkTree($path);
        self::writeLine($tree);
    }
    
    private static function _write($string, ...$params)
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


    public static function write($string, ...$params)
    {
        $result = self::_write($string, $params);
        if (!APP_IS_WEB) {
            print $result;
        } else {
            print $result;
            self::getLogger()->debug($result);
        }
    }
    
    public static function writeLine($string, ...$params)
    {
        $result = self::_write($string, $params);
        if (!APP_IS_WEB) {
            print $result . PHP_EOL;
        } else {
            print $result . PHP_EOL;
            self::getLogger()->debug($result . PHP_EOL);
        }
    }

    public static function writeException(\Throwable $ex, $file = null, $line = null)
    {
        if (!APP_IS_WEB) {
            $message = '';

            if($ex instanceof \ErrorException) {
                $message .= 'Error severity: ' . $ex->getSeverity() . PHP_EOL;
            }
            $message .= 'Error code: ' . $ex->getCode() . PHP_EOL;
            $message .= 'In ' . $ex->getFile() . ', line ' . $ex->getLine() . PHP_EOL;
            $message .= 'With the message: ' . $ex->getMessage() . PHP_EOL;
            $message .= 'Stack trace: ' . $ex->getTraceAsString() . PHP_EOL;
            
            print  "\e[41m\033[37m" . $message . "\e[0m\033[0m";
        } else {
            self::getLogger()->exception($ex, $file, $line);
        }
    }

}
