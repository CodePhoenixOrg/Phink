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

class TApplication extends TObject
{
    
    //put your code here
    const DEBUG_MODE = 'DEBUG';
    const TEST_MODE = 'TEST';
    const PROD_MODE = 'PROD';
    
    
    private static $_executionMode = self::PROD_MODE;
    private static $_verboseMode = false;
    private static $_useTransactions = true;
    private $_argv;
    private $_argc;
    private $_parameters = array();
    private $_name = 'program';
    private $_appDirectory = '';


    private $redis = null;

    public function __construct($argv = [], $argc = 0, $appDirectory = '') {

//        if(!class_exists('\Phink\TAutoloader')) {
//            include 'phink/autoloader.php';
//            \Phink\TAutoLoader::register();
//        }
        $this->_appDirectory = $appDirectory;
        
        $this->_name = $argv[0];
        $this->_argv = $argv;
        $this->_argc = $argc;
        
        if($this->getArgument('make-phar')) {
            $this->makePhar();
        }
        
        if($this->getArgument('require-master')) {
            $this->_requireMaster();
        }
        
    }
    
    public function getArgument($long, $short = '')
    {
        $result = false;
        $isFound = false;
        array_push($this->_parameters, ['long' => $long, 'short' => $short]);
        
        if(DOCUMENT_ROOT == '') {
            
            $c = count($this->_argv);
            for ($i = 0; $i < $c; $i++) {
                if ($this->_argv[$i] == '--' . $long)  {
                    if(isset($this->_argv[$i+1])) {
                        if(substr($this->_argv[$i+1], 0, 1) == '-') {
                            $result = true;
                        } else {
                            $result = $this->_argv[$i + 1];
                        }
                    } else {
                        $result = true;
                    }
                    $isFound = true;
                    break;
                } else if($this->_argv[$i] == '-' . $short) {

                    $sa = explode('=', $this->_argv[$i]);
                    if(count($sa) > 1) {
                        $result = $sa[1];
                    } else {
                        $result = true;
                    }
                    $isFound = true;
                    break;
                }
            }
        } else {
            if(isset($_REQUEST[$short])) {
                $result = $_REQUEST[$short];
                $isFound = true;
            } elseif(isset($_REQUEST[$long])) {
                $result = $_REQUEST[$long];
                $isFound = true;
            }
        }
        
        if(!$isFound) {
            $lonException = '';
            $several = '';
            if(isset($long)) {
                $lonException = '|' . $long;
                $several = 's';
            }
            
            //throw new InvalidArgumentException("Argument$several introuvable$several : " . $short . $lonException);
        }
        
        return $result;
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
        if(!$myExecutionMode) {
            $myExecutionMode = (DOCUMENT_ROOT == '') ?  'debug' : 'prod';
        }
        
        $prod = ($myExecutionMode == 'prod');
        $test = ($myExecutionMode == 'test' || $myExecutionMode == 'devel' || $myExecutionMode == 'dev');
        $debug = ($myExecutionMode == 'debug');
        
        if($prod) self::$_executionMode = self::PROD_MODE;
        if($test) self::$_executionMode = self::TEST_MODE;
        if($debug) self::$_executionMode = self::DEBUG_MODE;
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
        if(is_string($token)) {
            // avec ce token on récupère l'utilisateur et un nouveau token
            $token = TAuthentication::getUserCredentialsByToken($token);
        }
        
        return $token;
    }
        
    private static function _requireMaster()
    {
        if(!function_exists('curl_init')) {
            throw new \Exception("Please install curl extension");
        }

        if(!function_exists('zip_open')) {
            throw new \Exception("Please install zip extension");
        }

        $curl = new \Phink\Web\Curl();
        
        $result = $curl->request('https://codeload.github.com/dpjb71/Phink/zip/master');
        
//        var_dump($result);
        
        file_put_contents('master.zip', $result->content);
        
        $zip = new \Phink\Utils\Zip();
        $zip->deflat('master.zip');

        
    }
    
    public function makePhar() {
        if(DOCUMENT_ROOT != '') {
            throw new \Exception('Still cannot make a phar of a web application!');
        }
        ini_set('phar.readonly', 0);
        
         // the current directory must be src
        $srcRoot = $this->_appDirectory;
        $buildRoot = $this->_appDirectory . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'build';
        $pharName = $this->_name . ".phar";

        $phar = new \Phar(
            $buildRoot . DIRECTORY_SEPARATOR . $pharName
            , \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::KEY_AS_FILENAME
            , $pharName
        );
        
        $phar["app.php"] = file_get_contents($srcRoot . "/app.php");
        $phar["lib.php"] = file_get_contents($srcRoot . "/lib.php");
        $phar->setStub($phar->createDefaultStub("app.php"));
    }

}
