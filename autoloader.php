<?php

/*
 * This file was grabbed from Predis package.
 *
 * (c) Daniele Alessandri <suppakilla@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*
 * This file is modified by David Blanchard for CodePhink Project
 */

namespace Phink;

use Phink\Core\TRegistry;
use Phink\Core\TObject;
/**
 * Implements a lightweight PSR-0 compliant autoloader.
 *
 * @author Eric Naeseth <eric@thumbtack.com>
 * @author Daniele Alessandri <suppakilla@gmail.com>
 */
class TAutoloader
{
    private $directory;
    private $prefix;
    private $prefixLength;

    /**
     * @param string $baseDirectory Base directory where the source files are located.
     */
    public function __construct($baseDirectory = __DIR__)
    {
        $this->directory = $baseDirectory;
        $this->prefix = __NAMESPACE__ . '\\';
        $this->prefixLength = strlen($this->prefix);
    }

    /**
     * Registers the autoloader class with the PHP SPL autoloader.
     *
     * @param bool $prepend Prepend the autoloader on the stack instead of appending it.
     */
    public static function register($prepend = false)
    {
        spl_autoload_register(array(new self, 'autoload'), true, $prepend);
    }

    public static function classNameToFilename($className)
    {
        $translated = '';
        $className = substr($className, 1);
        $l = strlen($className);
        for($i = 0; $i < $l; $i++) {
            if(ctype_upper($className[$i])) {
                $translated .= '_' . strtolower($className[$i]);
            } else {
                $translated .= $className[$i];
            }
        }

        $translated = substr($translated, 1);

        return $translated;
    }


    /**
     * Loads a class from a file using its fully qualified name.
     *
     * @param string $className Fully qualified name of a class.
     */
    public function autoload($className)
    {
        if (0 === strpos($className, $this->prefix)) {
            $parts = explode('\\', substr($className, $this->prefixLength));
            $className = array_pop($parts);
            
            $translated = self::classNameToFilename($className);
            
            $filepath = $this->directory . DIRECTORY_SEPARATOR . strtolower(implode(DIRECTORY_SEPARATOR, $parts) . DIRECTORY_SEPARATOR . $translated);
            $filepath .= (file_exists($filepath . PREHTML_EXTENSION)) ? CLASS_EXTENSION : '.php';
            
            //file_put_contents(STARTER_FILE, "include '$filepath';"  . "\n", FILE_APPEND);
            include $filepath;
            
        }
    }
    
    private static function _includeInnerClass($viewName, $info, $withCode = true)
    {
        $className = ucfirst($viewName);
        $filename = ROOT_PATH . $info->path . \Phink\TAutoloader::classNameToFilename($viewName) . CLASS_EXTENSION;
        //\Phink\Log\TLog::debug('INCLUDE INNER PARTIAL CONTROLLER : ' . $filename, __FILE__, __LINE__);

        $code = file_get_contents($filename, FILE_USE_INCLUDE_PATH);
        
        $file = str_replace('\\', '_', $info->namespace . '\\' . $viewName) . '.php';
        if(!file_exists(TMP_DIR . DIRECTORY_SEPARATOR . $file)) {
            include $filename;
        }
       
        if($withCode) {
            $code = substr(trim($code), 0, -2) . CR_LF . CONTROL_ADDITIONS;
            TRegistry::setCode($filename, $code);
        }
        
        return ['file' => $file, 'type' => $info->namespace . '\\' . $className, 'code' => $code];
        
          
    }
    
    /**
     * Load the controller file, parse it in search of namespace and classname.
     * Alternatively execute the code if the class is not already declared 
     * 
     * @param string $filename The controller filename 
     * @param int $params The bitwise constants values that determine the behavior
     *                    INCLUDE_FILE : execute the code
     *                    RETURN_CODE : ...
     * @return boolean
     */
    public static function includeClass($filename, $params = 0)
    {

        $filename = strtolower($filename);
        if(!file_exists($filename)) {
            //\Phink\Log\TLog::debug('INCLUDE CLASS : FILE ' . $filename . ' DOES NOT EXIST');
            return false;
        }
        
        $classText = file_get_contents($filename, FILE_USE_INCLUDE_PATH);
        
        $code = $classText;
        
        $classText = str_replace("\r", '', $classText);
        $classText = str_replace("\n", '', $classText);
        
        $start = strpos($classText, 'namespace');
        $namespace = '';
        if ($start > 0) {
            $start += 10;
            $end = strpos($classText, ';', $start);
            $namespace = substr($classText, $start, $end - $start);
        }
        
        $start = strpos($classText, 'class');
        $className = '';
        if ($start > 0) {
            $start += 6;
            $end = strpos($classText, '{', $start);
            $className = substr($classText, $start, $end - $start);
            $className = trim($className);
            $sa = explode(' ', $className);
            $className = $sa[0];
        }
        
        $fqcn = $namespace . '\\' . $className;
        $file = str_replace('\\', '_', $fqcn) . '.php';
        if(isset($params) && ($params && RETURN_CODE === RETURN_CODE)) {
            $code = substr(trim($code), 0, -2) . CR_LF . CONTROL_ADDITIONS;
            TRegistry::setCode($filename, $code);
        }
        
        //include $filename;
        /*include ("data://text/plain," . '<?php ' . substr(str_replace("\t", '', str_replace("\n", '', str_replace("\r", '', $code))), 5) . ' ?>');
         * 
         */
        
        Log\TLog::debug(TMP_DIR . DIRECTORY_SEPARATOR . $file);
        
        if((isset($params) && ($params && INCLUDE_FILE === INCLUDE_FILE)) && !class_exists('\\' . $fqcn))  {
            include $filename;
        }
        
        return ['file' => $file, 'type' => $fqcn, 'code' => $code];
        
    }

    public static function includeModelByName($modelName)
    {
        $result = false;
        
        //\Phink\Log\TLog::debug('MODEL NAME : ' . $modelName, __FILE__, __LINE__);
        
        $modelFileName = 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . $modelName . CLASS_EXTENSION;
        
        $result = self::includeClass($modelFileName, INCLUDE_FILE);
        if(!$result) {
            $result['type'] = DEFALT_MODEL;
        }
        $result['file'] = $modelFileName;
        
        return $result;
    }
    
    public static function includeControllerByName($viewName)
    {
        $result = false;
        $controllerFileName = 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $viewName . DIRECTORY_SEPARATOR . $viewName . CLASS_EXTENSION;
        
        $result = self::includeClass($controllerFileName, RETURN_CODE | INCLUDE_FILE);
        if(!$result) {
            $sa = explode('.', SERVER_NAME);
            array_pop($sa);
            if(count($sa) == 2) {
                array_shift($sa);
            }
            $namespace = ucfirst($sa[0]);
            $namespace .= '\\Controllers'; 
            $className = ucfirst($viewName);
            
            $result = self::includeDefaultController($namespace, $className);
            TRegistry::setCode('app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $viewName . CLASS_EXTENSION, $result['code']);
        }

        return $result;
    }

    public static function includePartialControllerByName($viewName)
    {
        $result = false;
        $controllerFileName = 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $viewName . DIRECTORY_SEPARATOR . $viewName . CLASS_EXTENSION;
        if(file_exists($controllerFileName)) {
            //\Phink\Log\TLog::debug('INCLUDE CUSTOM PARTIAL CONTROLLER : ' . $controllerFileName, __FILE__, __LINE__);
            $result = self::includeClass($controllerFileName, RETURN_CODE | INCLUDE_FILE);
        } elseif ($info = Core\TRegistry::classInfo($viewName)) {
            $result = self::_includeInnerClass($viewName, $info, true);
        } else {
            //\Phink\Log\TLog::debug('INCLUDE DEFAULT PARTIAL CONTROLLER : ' . $controllerFileName, __FILE__, __LINE__);
            $namespace = self::getDefaultNamespace();
            $className = ucfirst($viewName);
            $result = self::includeDefaultPartialController($namespace, $className);
            TRegistry::setCode('app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $viewName . CLASS_EXTENSION, $result['code']);
            
        }

        return $result;
    }
    
    public static function includeDefaultController($namespace, $className)
    {
        $result['type'] = DEFAULT_CONTROLLER;
        $result['code'] = self::controllerTemplate($namespace, $className);
        $result['code'] = substr(trim($result['code']), 0, -2) . CONTROL_ADDITIONS;

        return $result;
    }

    public static function includeDefaultPartialController($namespace, $className)
    {
        $result['type'] = DEFAULT_PARTIAL_CONTROLLER;
        $result['code'] = self::partialControllerTemplate($namespace, $className);
        $result['code'] = substr(trim($result['code']), 0, -2) . CONTROL_ADDITIONS;

        return $result;
    }

    public static function import($viewName)
    {
        $result = false;
        $cacheFilename = '';
        $classFilename = '';
        
        if($info = Core\TRegistry::classInfo($viewName))
{
            $classFilename = ROOT_PATH . $info->path . \Phink\TAutoloader::classNameToFilename($viewName) . CLASS_EXTENSION;
            $cacheFilename = TMP_DIR . DIRECTORY_SEPARATOR . str_replace(DIRECTORY_SEPARATOR, '_', ROOT_PATH . $info->path . \Phink\TAutoloader::classNameToFilename($viewName)) . CLASS_EXTENSION;
        } else {
            $classFilename = 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $viewName . DIRECTORY_SEPARATOR . $viewName . CLASS_EXTENSION;
            $cacheFilename = TMP_DIR . DIRECTORY_SEPARATOR . 'app_controllers_' . $viewName . '_' . $viewName . CLASS_EXTENSION;
        }
        
        if(file_exists($cacheFilename)) {
            //\Phink\Log\TLog::debug('INCLUDE CACHED CONTROL: ' . $cacheFilename, __FILE__, __LINE__);
            $include = file_get_contents($cacheFilename);
            TRegistry::setCode($classFilename, $include);
            include $cacheFilename;
        } else {
            //\Phink\Log\TLog::debug('INCLUDE NEW CONTROL : ' . $viewName, __FILE__, __LINE__);
            //include 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $viewName . DIRECTORY_SEPARATOR . $viewName . CLASS_EXTENSION;
            $result = self::includePartialControllerByName($viewName);
        }

        return $result;
    }
    
    public static function getDefaultNamespace()
    {
        $sa = explode('.', SERVER_NAME);
        array_pop($sa);
        if(count($sa) == 2) {
            array_shift($sa);
        }

        return ucfirst(str_replace('-', '_', $sa[0])) . '\\Controllers';
    }
    
    public static function controllerTemplate($namespace, $className)
    {
        $result = <<<CONTROLLER
<?php
namespace $namespace;

use Phink\MVC\TController;

class $className extends TController
{
    
       
}
CONTROLLER;
        
        return $result;
        
    }

    public static function partialControllerTemplate($namespace, $className)
    {
        $result = <<<CONTROLLER
<?php
namespace $namespace;

use Phink\MVC\TPartialController;

class $className extends TPartialController
{
    
       
}
CONTROLLER;
        
        return $result;
        
    }
    
}
