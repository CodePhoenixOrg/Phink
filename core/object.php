<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Phoenix\Core;

use \ReflectionClass;
/**
 * Description of TObject
 *
 * @author david
 */

class TObject
{
    //put your code here
    
    protected $parent = null;
    private $_reflection = NULL;
    protected $id = 'noname';
    protected $serialFilename = '';
    protected $isSerialized = '';

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        \Phoenix\Log\TLog::dump(__CLASS__ . ':' . __METHOD__, $value);
        $this->id = $value;
    }

    public function isAwake()
    {
        return $this->isSerialized;
    }

    public function getReflection()
    {
        if($this->_reflection == NULL) {
            $this->_reflection = new ReflectionClass(get_class($this));
        }
        return $this->_reflection;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent(TObject $parent)
    {
        $this->parent = $parent;
    }
    
    public function getFullType()
    {
        return get_class($this);
    }

    public function getNamespace()
    {
        $typeParts = explode('\\', get_class($this));
        array_pop($typeParts);
        return (count($typeParts) > 0) ? implode('\\', $typeParts) : '';
    }

    public function getType()
    {
        $typeParts = explode('\\', get_class($this));
        return array_pop($typeParts);
        
    }

    public function getBaseType()
    {
        return get_parent_class($this);
    }

    public function getFileName()
    {
        $reflection = $this->getReflection();
        return $reflection->getFileName();
    }

    public static function create($params = array())
    {
        $class = __CLASS__;
        $object = null;
        
        if(count($params) > 0) {
            $object = new $class();
        } else {
            $object = new $class($params);
        }
        
        return $object;
    }
    
    public function serialize()
    {
        //return serialize($this);
        $this->_reflection = $this->getReflection();
        $methods = $this->_reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        
        $result = print_r($methods, true);
        
        return $result;
        
    }
    
    public function unserialize($serialized)
    {
        //return (object)unserialize($serialized);
    }

    public function sleep()
    {
        $this->serialFilename = TMP_DIR . DIRECTORY_SEPARATOR . $this->id . JSON_EXTENSION;
        $this->isSerialized = true;
        
//        $phpObject = var_export($this, true);
//        $objectFilename = TMP_DIR . DIRECTORY_SEPARATOR . $this->id . '.obj.txt';
//        file_put_contents($objectFilename, $phpObject);

        file_put_contents($this->serialFilename, $this->serialize());
    }
    
    public function wake()
    {
        $serialFilename = TMP_DIR . DIRECTORY_SEPARATOR . $this->id . JSON_EXTENSION;
        $result = file_exists($serialFilename);
        
        $serialized = ($result) ? file_get_contents($serialFilename) : $result;
        
        return ($serialized) ? unserialize($serialized) : $result;
    }

    public static function wakeUp($id)
    {
        $serialFilename = TMP_DIR . DIRECTORY_SEPARATOR . $id . JSON_EXTENSION;
        $result = file_exists($serialFilename);
        
        $serialized = ($result) ? file_get_contents($serialFilename) : $result;
        
        return ($serialized) ? unserialize($serialized) : $result;
    }
    
    public static function arraysToObjects(array $value)
    {
        $result = array();
        
        foreach ($value as $array) {
            array_push($result, (object) $array);
        }
        
        return $result;
    }
}
