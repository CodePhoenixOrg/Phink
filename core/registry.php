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
                'path' => DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'ui' . DIRECTORY_SEPARATOR . 'widget' . DIRECTORY_SEPARATOR . 'pager' . DIRECTORY_SEPARATOR, 
                'namespace' => ROOT_NAMESPACE . '\Web\UI\Widget\Pager', 
                'hasTemplate' => true, 
                'canRender' => true
            )
            , 'TPluginRenderer' => array(
                'alias' => 'pluginrenderer',
                'path' => DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'ui' . DIRECTORY_SEPARATOR, 
                'namespace' => ROOT_NAMESPACE . '\Web\UI', 
                'hasTemplate' => false, 
                'canRender' => true
            ) 
            , 'TPlugin' => array(
                'alias' => 'plugin',
                'path' => DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'ui' . DIRECTORY_SEPARATOR . 'widget' . DIRECTORY_SEPARATOR . 'plugin' . DIRECTORY_SEPARATOR, 
                'namespace' => ROOT_NAMESPACE . '\Web\UI\Widget\Plugin', 
                'hasTemplate' => true, 
                'canRender' => true
            )
            , 'TPluginChild' => array(
                'alias' => 'pluginchild',
                'path' => DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'ui' . DIRECTORY_SEPARATOR . 'widget' . DIRECTORY_SEPARATOR . 'plugin' . DIRECTORY_SEPARATOR, 
                'namespace' => ROOT_NAMESPACE . '\Web\UI\Widget\Plugin', 
                'hasTemplate' => false, 
                'canRender' => false
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
