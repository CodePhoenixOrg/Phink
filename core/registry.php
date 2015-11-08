<?php

namespace Phoenix\Core;

/**
 * Description of registry
 *
 * @author david
 */

class TRegistry
{
    
    private static $_classRegistry = null;
    
    public static function init() {
        if(self::$_classRegistry) return;
        
        self::$_classRegistry = array (
            'TPager' => array(
                'alias' => 'pager',
                'path' => DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'ui' . DIRECTORY_SEPARATOR, 
                'namespace' => ROOT_NAMESPACE . '\Web\UI', 
                'hasTemplate' => true, 
                'canRender' => true
             ),
            'TGrid' => array(
                'alias' => 'grid',
                'path' => DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'ui' . DIRECTORY_SEPARATOR, 
                'namespace' => ROOT_NAMESPACE . '\Web\UI', 
                'hasTemplate' => false, 
                'canRender' => true
            ),
            'TDataColumn' => array(
                'alias' => 'column',
                'path' => DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'ui' . DIRECTORY_SEPARATOR, 
                'namespace' => ROOT_NAMESPACE . '\Data\UI', 
                'hasTemplate' => false, 
                'canRender' => false
            ),
            'TDataGrid' => array(
                'alias' => 'datagrid',
                'path' => DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'ui' . DIRECTORY_SEPARATOR, 
                'namespace' => ROOT_NAMESPACE . '\Data\UI', 
                'hasTemplate' => true, 
                'canRender' => true
            )
        );
    
    }
    
    public static function classInfo($className = '')
    {
        self::init();
        return (isset(self::$_classRegistry[$className]) ? (object) self::$_classRegistry[$className] : false);
    }
    
    public static function classPath($className = '')
    {
        $classInfo = self::classInfo($className);
        return ($classInfo) ? $classInfo->path : '';
    }    

    public static function classNamespace($className = '')
    {
        $classInfo = self::classInfo($className);
        return ($classInfo) ? $classInfo->namespace : '';
    }    

    public static function classHasTemplate($className = '')
    {
        $classInfo = self::classInfo($className);
        return ($classInfo) ? $classInfo->hasTemplate : '';
    }    
    
    public static function classCanRender($className = '')
    {
        $classInfo = self::classInfo($className);
        return ($classInfo) ? $classInfo->canRender : '';
    }    
}
