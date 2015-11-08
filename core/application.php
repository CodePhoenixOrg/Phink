<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Phoenix\Core;

//$single_server = array(
//    'host' => '192.168.1.8',
//    'port' => 6379,
//    'database' => 15
//);

require_once 'constants.php';

//include 'phoenix/'.STARTER_FILE;

//require_once 'log.php';
//require_once 'object.php';
//require_once 'request.php';
//require_once 'phoenix/mvc/view.php';
//require_once 'phoenix/utils/file_utils.php';
//require_once 'phoenix/auth/authentication.php';
require_once 'phoenix/autoloader.php';
\Phoenix\TAutoLoader::register();

//require_once 'Predis/Autoloader.php';
//\Predis\Autoloader::register();

/**
 * Description of Application
 *
 * @author david
 */

use Phoenix\Core\TObject;
use Phoenix\MVC\TView;
use Phoenix\Auth\TAuthentication;

class TApplication extends TObject
{
    
    //put your code here
    const DEBUG_MODE = 'DEBUG';
    const TEST_MODE = 'TEST';
    const PROD_MODE = 'PROD';
    
    // Récupère tous les clubs dans un tableau associatif sous la forme numéro=>nom
    private static $_executionMode = self::PROD_MODE;
    private static $_verboseMode = false;
    private static $_useTransactions = true;
    private static $_log = null;
    private $_argv;
    private $_parameters = array();
    
    private $redis = null;
    
    public function getArgument($short, $long = '')
    {
        $result = false;
        $isFound = false;
        array_push($this->_parameters, array('short' => $short, 'long' => $long));
        
        if(DOCUMENT_ROOT == '') {
            for ($i = 0; $i < count($this->_argv); $i++) {
                if ($this->_argv[$i] == '--' . $long)  {
                    if(isset($this->_argv[$i+1])) {
                        if(substr($this->_argv[$i+1], 0, 1) == '-') {
                            $result = true;
                        } else {
                            $result = $this->_argv[$i+1];
                        }
                    } else {
                        $result = true;
                    }
                    $isFound = true;
                    break;
                } else if(strstr($this->_argv[$i], '-' . $short)) {

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

}
