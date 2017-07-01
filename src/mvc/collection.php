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
 
 namespace Phink\MVC;

/**
 * Description of Collection
 *
 * @author david
 */
use Phink\Core\TObject;

interface ICollection {

    public function add($object);
    public function set($object, $index);
    public function addAt($object, $index);
    public function remove($object);
    public function contains($object);
    public function count();

}

abstract class TCollection extends TModel implements \Iterator
{
        //put your code here
    protected $innerList = array();
    private $_count = NULL;

    public function __construct(TObject $parent = null)
    {
        parent::__construct($parent);
    }
    
    
    public function setCollection(array $collection = array())
    {
        $this->innerList = $collection;
    }

    // M�thodes de l'interface Iterator
    // DEBUT
    public function rewind()
    {
            $this->position = 0;
    }

    public function current()
    {
        return $this->innerList[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->innerList[$this->position]);
    }
    // M�thodes de l'interface Iterator
    // FIN
    
    
    public function count()
    {
        if($this->_count == NULL) {
            $this->_count = count($this->innerList);
        }
        return $this->_count;
    }

    public function items($index)
    {
        return $this->innerList[$index];
    }

    public function add(TObject $object)
    {
        array_push($this->innerList, $object);
        $this->_count = count($this->innerList);
        $this->setDirty();
        return $this->_count - 1;
    }

//    public function addAt(TObject $object, $index)
//    {
//        $current = array();
//        $current[0] = $object;
//        $current[1] = $this->innerList[$index];
//
//        array_splice($this->innerList, $index, $current);
//        $this->_count = count($this->innerList);
//        $this->setDirty();
//    }

    public function set(TObject $object, $index)
    {
        $this->innerList[$index] = $object;
        $this->setDirty();
    }

//    public function remove(TObject $object)
//    {
//        if($index = $this->contains($object)) {
//            $this->removeAt($index);
//        }
//    }

    public function removeAt($index)
    {
        unset($this->innerList[$index]);
        $this->_count = count($this->innerList);
        $this->setDirty();
    }

    public function contains(TObject $object)
    {
        $result = array_search($object, $this->innerList);

        return $result;
    }

    public function clear()
    {
        for($i = $this->_count - 1; $i > -1; $i--) {
            unset($this->innerList[$i]);
        }
    }

    public function toArray()
    {
        return $this->innerList;
    }

    public function toString()
    {
        $result = '';
        for($i = 0; $i < $this->count(); $i++) {
            $result .= $this->innerList[$i] . "\n";
        }

        return $result;
    }
    
    public function getSelectQuery()
    {
        return false;
    }

    public function getInsertQuery()
    {
        return false;
    }

    public function getUpdateQuery()
    {
        return false;
    }

    public function getDeleteQuery()
    {
        return false;
    }

    public function select()
    {
        return false;
    }
    
    public function insert()
    {
        $result = false;
        $this->checkDirty();
        if(!$this->isDirty) return $result;
        if($this->isFromDB) return $result;

        if($result = TConnector::beginTransaction($this->setTransaction)) {
        
            foreach($this as $item) {
                $item->setIndex($this->position);
                $insert = $item->insert();
                $result = $insert && $result;
                if($result === false) {
                    $rollback = TConnector::rollback();
                    $result = $rollback && $result;
                    break;
                }
            }

            if($result === true) {
                if(TApplication::isProd() || TApplication::isTest()) {
                    $commit = TConnector::commit();
                } else {
                    $commit = TConnector::rollback();
                }
                
                $result = $commit && $result;
            }
        }
        
        $this->setFromDB();
        $this->isDirty = false;
        
        return $result;
    }
    
    public function update()
    {
        $result = false;
        $this->checkDirty();
        if(!$this->isDirty) return $result;
        if(!$this->isFromDB) return $result;
             
        if($result = TConnector::beginTransaction($this->setTransaction)) {
        
            foreach($this as $item) {
                
                $update = $item->update();
                $result = $update && $result;
                if($result === false) {
                    $rollback = TConnector::rollback();
                    $result = $rollback && $result;
                    break;
                }
            }

            if($result === true) {
                if(TApplication::isProd() || TApplication::isTest()) {
                    $commit = TConnector::commit();
                } else {
                    $commit = TConnector::rollback();
                }
                
                $result = $commit && $result;
            }
        }
        
        $this->setFromDB();
        $this->isDirty = false;
        
        return $result;
    }


    public function delete()
    {
        return false;
    }

    public function checkDirty()
    {
        foreach ($this as $child) {
            if($child->isDirty()) {
                $this->setDirty();
                break;
            }
        }
    }
    
    public function convertToWincaramy()
    {
        foreach($this as $item) {
            $item->setWincaramy(true);
        }
        
        $this->insert();
        
        foreach($this as $item) {
            $item->setWincaramy(false);
        }
        
        $this->update();
    }
}

?>
