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

    public function init() : void
    {
    }

    public function run() : bool
    {
        return true;
    }

    public function __construct(array $argv = [], int $argc = 0, string $appDirectory = '.')
    {
        parent::__construct();

        $this->_argv = $argv;
        $this->_argc = $argc;

        $this->scriptName = $argv[0];

        $this->appDirectory = $appDirectory . DIRECTORY_SEPARATOR;
    
        $path = explode(DIRECTORY_SEPARATOR, $this->appDirectory);
        $scriptDir = $this->appDirectory . '..' . DIRECTORY_SEPARATOR;
        $siteDir = $scriptDir . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
        $siteDir = realpath($siteDir) . DIRECTORY_SEPARATOR;
        $scriptDir = realpath($scriptDir) . DIRECTORY_SEPARATOR;

        array_pop($path);
        array_pop($path);
        $this->appName = array_pop($path);

        if (\Phar::running() !== '') {
            $this->appName = pathinfo($argv[0])['filename'];
            $this->appDirectory = str_replace('phar://', '', $scriptDir);
        }

        define('APP_NAME', $this->appName);
        
        define('SRC_ROOT', $siteDir . DIRECTORY_SEPARATOR);
        define('SCRIPT_ROOT', $scriptDir);

        if (APP_NAME == 'egg') {
            define('PHINK_ROOT', @realpath(SCRIPT_ROOT . '..' . DIRECTORY_SEPARATOR . 'phink') . DIRECTORY_SEPARATOR);
        } else {
            define('PHINK_ROOT', SRC_ROOT . 'vendor' . DIRECTORY_SEPARATOR . 'phink' . DIRECTORY_SEPARATOR . 'phink' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'phink' . DIRECTORY_SEPARATOR);
        }

        define('APP_ROOT', SRC_ROOT . 'app' . DIRECTORY_SEPARATOR);
        define('APP_SCRIPTS', APP_ROOT . 'scripts' . DIRECTORY_SEPARATOR);
        define('APP_DATA', SRC_ROOT . 'data' . DIRECTORY_SEPARATOR);
        define('APP_BUSINESS', APP_ROOT . 'business' . DIRECTORY_SEPARATOR);
        define('CONTROLLER_ROOT', APP_ROOT . 'controllers' . DIRECTORY_SEPARATOR);
        define('BUSINESS_ROOT', APP_ROOT . 'business' . DIRECTORY_SEPARATOR);
        define('MODEL_ROOT', APP_ROOT . 'models' . DIRECTORY_SEPARATOR);
        define('REST_ROOT', APP_ROOT . 'rest' . DIRECTORY_SEPARATOR);
        define('VIEW_ROOT', APP_ROOT . 'views' . DIRECTORY_SEPARATOR);
        define('CACHE_DIR', SRC_ROOT . 'cache' . DIRECTORY_SEPARATOR);

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

    protected function ignite() : void
    {
        parent::ignite();
        
        if (!APP_IS_PHAR) {
            $this->setCommand(
                'make-master-phar',
                '',
                'Make a phar archive of the current application with files from the master repository.',
                function () {
                    $this->makeMasterPhar();
                }
            );
            $this->setCommand(
                'make-phar',
                '',
                'Make a phar archive of the current application with files in vendor directory.',
                function () {
                    $this->makeVendorPhar();
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
            'running',
            '',
            'Show Phar::running() output',
            function () {
                self::writeLine('Phar::running():' . \Phar::running());
            }
        );
    
        $this->setCommand(
            'source-path',
            '',
            'Display the running application source directory.',
            function () {
                $this->writeLine($this->getApplicationDirectory());
            }
        );

        $this->setCommand(
            'script-path',
            '',
            'Display the running application root.',
            function () {
                $this->writeLine(SCRIPT_ROOT);
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
            'display-phink-tree',
            '',
            'Display the tree of the Phink framework.',
            function () {
                $this->displayPhinkTree();
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
                    $this->writeException($ex);
                }
            }
        );

        $this->setCommand(
            'show-arguments',
            '',
            'Show the application arguments.',
            function (callable $callback = null) {
                $data = ['argv' => $this->_argv, 'argc' => $this->_argc];
                $this->writeLine($data);

                if ($callback !== null) {
                    \call_user_func($callback, $data);
                }
            }
        );
    }
    
    protected function execute() : void
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

    protected function displayConstants() : array
    {
        try {
            $constants = [];
            $constants['PHINK_ROOT'] = PHINK_ROOT;

            $constants['APP_NAME'] = APP_NAME;
            $constants['LOG_PATH'] = LOG_PATH;
            $constants['DEBUG_LOG'] = DEBUG_LOG;
            $constants['ERROR_LOG'] = ERROR_LOG;

            if (APP_NAME !== 'egg') {
                $constants['SRC_ROOT'] = SRC_ROOT;
                $constants['APP_ROOT'] = APP_ROOT;
                $constants['APP_SCRIPTS'] = APP_SCRIPTS;
                $constants['APP_BUSINESS'] = APP_BUSINESS;
                $constants['MODEL_ROOT'] = MODEL_ROOT;
                $constants['VIEW_ROOT'] = VIEW_ROOT;
                $constants['CONTROLLER_ROOT'] = CONTROLLER_ROOT;
                $constants['REST_ROOT'] = REST_ROOT;
                $constants['APP_DATA'] = APP_DATA;
                $constants['CACHE_DIR'] = CACHE_DIR;
            }

            $constants['SCRIPT_ROOT'] = SCRIPT_ROOT;

            $this->writeLine('Application constants are :');
            foreach ($constants as $key => $value) {
                $this->writeLine("\033[0m\033[0;36m" . $key . "\033[0m\033[0;33m" . ' => ' . "\033[0m\033[0;34m" . $value . "\033[0m\033[0m");
            }

            return $constants;
        } catch (\Throwable $ex) {
            $this->writeException($ex);
            return [];
        }
    }

    private function _requireMaster() : \stdClass
    {
        $result = [];

        $libRoot = $this->appDirectory . '..' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR;

        if (!file_exists($libRoot)) {
            mkdir($libRoot);
        }

        $master = $libRoot . 'master';
        $filename = $master . '.zip';
        $phinkDir = $master . DIRECTORY_SEPARATOR . 'Phink-master' . DIRECTORY_SEPARATOR. 'src' . DIRECTORY_SEPARATOR . 'phink' . DIRECTORY_SEPARATOR;

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
            $tree = \Phink\Utils\TFileUtils::walkTree($phinkDir, ['php']);
        }
        
        $result = ['path' => $phinkDir, 'tree' => $tree];
        
        return (object)$result;
    }
    
    private function _requireVendor(string $path = '') : \stdClass
    {
        $result = [];

        $tree = \Phink\Utils\TFileUtils::walkTree(PHINK_ROOT, ['php']);

        $result = ['path' => PHINK_ROOT, 'tree' => $tree];
        
        return (object)$result;
    }

    public function addFileToPhar($file, $name) : void
    {
        $this->writeLine("Adding %s as %s", $file, $name);
        $this->_phar->addFile($file, $name);
    }

    public function makeMasterPhar() : void
    {
        $phinkTree = $this->_requireMaster();
        $this->_makePhar($phinkTree);
    }

    public function makeVendorPhar() : void
    {
        $phinkTree = $this->_requireVendor();
        $this->_makePhar($phinkTree);        
    }

    private function _makePhar(\stdClass $phinkTree) : void
    {
        try {

            // if (APP_IS_WEB) {
            //     throw new \Exception('Still cannot make a phar of a web application!');
            // }
            ini_set('phar.readonly', 0);
        
            // the current directory must be src
            $pharName = $this->appName . ".phar";
            $buildRoot = $this->appDirectory . '..' . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR;
        
            if (file_exists($buildRoot . $pharName)) {
                unlink($buildRoot . $pharName);
            }

            if (!file_exists($buildRoot)) {
                mkdir($buildRoot);
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
        
            $this->writeLine('APP_DIR::' . $this->appDirectory);
            $this->addPharFiles();

            $phinkDir = $phinkTree->path;
            $phink_builder = $phinkDir . 'phink_library.php';

            $phink_builder = \Phink\Utils\TFileUtils::relativePathToAbsolute($phink_builder);
            $this->addFileToPhar($phink_builder, "phink_library.php");
        
            foreach ($phinkTree->tree as $file) {
                $filename = $phinkTree->path . $file;
                $filename = realpath($filename);
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
            $execname = $buildRoot . $this->appName;
            if (PHP_OS == 'WINNT') {
                $execname .= '.bat';
            }

            rename($buildRoot . $this->appName . '.phar', $execname);
            chmod($execname, 0755);

        } catch (\Throwable $ex) {
            $this->writeException($ex);
        }
    }
    
    public function addPharFiles() : void
    {
        try {
            $tree = \Phink\Utils\TFileUtils::walkTree($this->appDirectory, ['php']);
    
            if (isset($tree[$this->appDirectory . $this->scriptName])) {
                unset($tree[$this->appDirectory . $this->scriptName]);
                $this->addFileToPhar($this->appDirectory . $this->scriptName, $this->scriptName);
            }
            foreach ($tree as $filename) {
                $this->addFileToPhar($this->appDirectory . $filename, $filename);
            }
        } catch (\Throwable $ex) {
            $this->writeException($ex);
        }
    }

    public function displayPhinkTree() : void
    {
        // $tree = [];
        // \Phink\Utils\TFileUtils::walkTree(PHINK_ROOT, $tree);
        $tree = \Phink\Utils\TFileUtils::walkTree(PHINK_ROOT);

        $this->writeLine($tree);
    }
    
    public function displayTree($path) : void
    {
        $tree = \Phink\Utils\TFileUtils::walkTree($path);
        $this->writeLine($tree);
    }

    public static function write($string, ...$params) : void
    {
        $result = self::_write($string, $params);
        if (!APP_IS_WEB) {
            print $result;
        } else {
            self::getLogger()->debug($result);
        }
    }
    
    public static function writeLine($string, ...$params) : void
    {
        $result = self::_write($string, $params);
        if (!APP_IS_WEB) {
            print $result . PHP_EOL;
        } else {
            self::getLogger()->debug($result . PHP_EOL);
        }
    }

    public static function writeException(\Throwable $ex, $file = null, $line = null) :void
    {
        if (!APP_IS_WEB) {
            $message = '';

            if ($ex instanceof \ErrorException) {
                $message .= 'Error severity: ' . $ex->getSeverity() . PHP_EOL;
            }
            $message .= 'Error code: ' . $ex->getCode() . PHP_EOL;
            $message .= 'In ' . $ex->getFile() . ', line ' . $ex->getLine() . PHP_EOL;
            $message .= 'With the message: ' . $ex->getMessage() . PHP_EOL;
            $message .= 'Stack trace: ' . $ex->getTraceAsString() . PHP_EOL;
            
            print  "\033[41m\033[1;37m" . $message . "\033[0m\033[0m";
        } else {
            self::getLogger()->exception($ex, $file, $line);
        }
    }
}
