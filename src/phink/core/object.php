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
 
 
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Phink\Core;

use \ReflectionClass;

/**
 * Description of TObject
 *
 * @author david
 */

class TObject extends TStaticObject
{
    //put your code here
    
    private $_reflection = null;
    protected $parent = null;
    protected $id = 'noname';
    protected $serialFilename = '';
    protected $isSerialized = '';
    protected $children = array();
    protected $fqClassName = '';
    

    public function __construct(TObject $parent = null)
    {
        $this->parent = $parent;
    }


    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        //self::$logger->dump(__CLASS__ . ':' . __METHOD__, $value);
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

    public function getMethodParameters($method)
    {
        $ref = $this->getReflection();
        $met = $ref->getMethod($method);
        
        $params = [];
        foreach($met->getParameters() as $currentParam)  {
            array_push($params, $currentParam->name);
        }
        
        return $params;
    }
    
    public function validate($method) {
        if ($method == '') return false;
        
        $result = [];
        
        if(!method_exists($this, $method)) {
            throw new \BadMethodCallException($this->getFQClassName() . "::$method is undefined");
        } else {

            $params = $this->getMethodParameters($method);
            
            $args = $_REQUEST;
            if(isset($args['PHPSESSID'])) unset($args['PHPSESSID']);
            if(isset($args['action'])) unset($args['action']);
            if(isset($args['token'])) unset($args['token']);
            if(isset($args['q'])) unset($args['q']);
            if(isset($args['_'])) unset($args['_']);
            $args = array_keys($args);
            
            $validArgs = [];
            foreach($args as $arg) {
                if(!in_array($arg, $params)) {
                    throw new \BadMethodCallException($this->getFQClassName() . "::$method::$arg is undefined");
                } else {
                    array_push($validArgs, $arg);
                }
            }
            foreach($params as $param) {
                if(!in_array($param, $validArgs)) {
                    throw new \BadMethodCallException($this->getFQClassName() . "::$method::$param is missing");
                } else {
                    $result[$param] = \Phink\Web\TRequest::getQueryStrinng($param);
                }
            }
            
        }

        return $result;
    }

    public function invoke($method, $params = array())
    {
        $values = array_values($params);
        
        if(count($values) > 0) {
            $args = '"' . implode('", "', $values) . '"';
            self::$logger->debug(__METHOD__ . '::INVOKE_ACTION::' . $method  . '(' . $args . ')');
//            include 'data://text/plain;base64,' . base64_encode('<?php $this->' . $method  . '(' . $args . ')');
            $ref = new \ReflectionMethod($this->getFQClassName(), $method);
            $ref->invokeArgs($this, $values);
        } else {
            self::$logger->debug(__METHOD__ . '::INVOKE_ACTION::' . $method  . '()');
//            $ref->invoke($this);
            $this->$method();
        }        
    }
    
    public function getParent()
    {
        return $this->parent;
    }

    public function setParent(TObject $parent)
    {
        $this->parent = $parent;
    }
    
    public function addChild(TObject $child)
    {
        $this->children[$child->getId()] = $child;
    }
    
    public function removeChild(TObject $child)
    {
        unset($this->children[$child->getId()]);
    }

    public function getChildById($id)
    {
        $result = null;
            
        if(array_key_exists($id, $this->children)) {
            $result = $this->children[$id];
        }
        
        return $result;
    }
    
    public function getChildrenIds()
    {
        return array_keys($this->children);
    }

    public function getFullType()
    {
        return get_class($this);
    }

    public function getNamespace()
    {
        $typeParts = explode('\\', $this->getFQClassName());
        array_pop($typeParts);
        return (count($typeParts) > 0) ? implode('\\', $typeParts) : '';
    }

    public function getFQClassName() 
    {
        if($this->fqClassName == '') {
            $this->fqClassName = get_class($this);
        }
        return $this->fqClassName;
    }
    
    public function getType()
    {
        $typeParts = explode('\\', $this->getFQClassName());
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
        $this->serialFilename = RUNTIME_DIR . $this->id . JSON_EXTENSION;
        $this->isSerialized = true;
        
//        $phpObject = var_export($this, true);
//        $objectFilename = RUNTIME_DIR . $this->id . '.obj.txt';
//        file_put_contents($objectFilename, $phpObject);

        file_put_contents($this->serialFilename, $this->serialize());
    }
    
    public function wake()
    {
        $serialFilename = RUNTIME_DIR . $this->id . JSON_EXTENSION;
        $result = file_exists($serialFilename);
        
        $serialized = ($result) ? file_get_contents($serialFilename) : $result;
        
        return ($serialized) ? unserialize($serialized) : $result;
    }

    public static function wakeUp($id)
    {
        $serialFilename = RUNTIME_DIR . $id . JSON_EXTENSION;
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
