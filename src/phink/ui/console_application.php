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
     
        $this->_argv = $argv;
        $this->_argc = $argc;

        if (\Phar::running() !== '') {
            $this->_name = $argv[0];
        }

        $this->appDirectory = $appDirectory . DIRECTORY_SEPARATOR;
    
        $useTransaction = $this->setParameter('useTransactions');
        $execution = $this->setParameter('executionMode');
        $verbose = $this->setParameter('verbose');

        self::setExecutionMode($execution);
        self::useTransactions($useTransaction);
        
        $this->ignite();

        $this->init();
        $this->run();
        $this->execute();
    }

    protected function ignite()
    {
        parent::ignite();
        
        $this->setParameter(
            'make-phar',
            '',
            'Make a phar archive of the current application.',
            function () {
                $this->makePhar();
            }
        );
        
        $this->setParameter(
            'require-master',
            '',
            'Download the ZIP file of the master branch of Phink framework.',
            function () {
                $this->_requireMaster();
            }
        );
        
        $this->setParameter(
            'display-tree',
            '',
            'Display the tree of the current application.',
            function () {
                $this->displayTree($this->appDirectory);
            }
        );

        $this->setParameter(
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
        foreach ($this->parameters as $long => $param) {
            $short = $param['short'];
            $callback = $param['callback'];
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
            if($isFound) break;
        }

        if ($callback !== null && $isFound && $result === null) {
            call_user_func($callback);
        } elseif ($callback !== null && $isFound && $result !== null) {
            call_user_func($callback, $result);
        }
    }

    private static function _requireMaster()
    {
        $result = [];
        $dirname = 'master';
        $filename = $dirname . '.zip';

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
        
        if (file_exists($dirname)) {
            $phinkDir = 'master' . DIRECTORY_SEPARATOR . 'Phink-master' . DIRECTORY_SEPARATOR. 'src' . DIRECTORY_SEPARATOR . 'phink';
            //$phinkDir = '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'phink';
            $tree = \Phink\TAutoloader::walkTree($phinkDir, ['php']);
        }
        
        $result = ['path' => $phinkDir, 'tree' => $tree];
        
        return (object)$result;
    }
    
    public function addFileToPhar($file, $name)
    {
        $this->_phar->addFile($file, $name);
    }
    
    public function makePhar()
    {
        // if (APP_IS_WEB) {
        //     throw new \Exception('Still cannot make a phar of a web application!');
        // }
        ini_set('phar.readonly', 0);
        
        // the current directory must be src
        $srcRoot = $this->appDirectory;
        $buildRoot = $srcRoot . '..' . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR;
        $pharName = $this->_name . ".phar";
        
        if (file_exists($buildRoot . $pharName)) {
            unlink($buildRoot . $pharName);
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
        
        $this->addPharFiles();

        $master = self::_requireMaster();
        $phinkDir = 'master' . DIRECTORY_SEPARATOR . 'Phink-master' . DIRECTORY_SEPARATOR. 'src' . DIRECTORY_SEPARATOR . 'phink' . DIRECTORY_SEPARATOR;
        $phink_builder = $phinkDir . 'phink_library.php';

        $phink_builder = \Phink\Utils\TFileUtils::relativePathToAbsolute($phink_builder);
        $this->addFileToPhar($phink_builder, "phink_library.php");
        
        foreach ($master->tree as $file) {
            $filename = $srcRoot . $master->path . $file;
            
            $filename = \Phink\Utils\TFileUtils::relativePathToAbsolute($filename);
            
            $info = pathinfo($filename, PATHINFO_BASENAME);
//            self::writeLine(print_r($info, true));
            
            self::writeLine("Adding %s as %s", $filename, $info);
            $this->_phar->addFile($filename, $info);
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
    }
    
    public function addPharFiles()
    {
        $tree = \Phink\TAutoloader::walkTree($this->appDirectory, ['php']);
        if (isset($tree['app.php'])) {
            unset($tree['app.php']);
            $this->addFileToPhar($this->appDirectory . "app.php", "app.php");
        }
        foreach ($tree as $filename) {
            $this->addFileToPhar($this->appDirectory . $filename, $filename);
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
            self::getLogger()->debug($result);
        }
    }
    
    public static function writeLine($string, ...$params)
    {
        $result = self::_write($string, $params);
        if (!APP_IS_WEB) {
            print $result . PHP_EOL;
        } else {
            self::getLogger()->debug($result . PHP_EOL);
        }
    }

    public static function writeException($ex, $file = null, $line = null)
    {
        if (!APP_IS_WEB) {
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
        if (!APP_IS_WEB) {
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
