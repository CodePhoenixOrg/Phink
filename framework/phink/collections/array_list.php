<?php
/*
 * Copyright (C) 2019 David Blanchard
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
 
namespace Phink\Collections;

use Phink\Core\TObject;

/**
 * Description of aarraylist
 *
 * @author david
 */
interface IArrayList {

    public function add($object);
    public function removeAt($index);
    public function contains($object);
    public function count();

}

class TArrayList extends TObject implements IArrayList
{
    //put your code here
    private $_innerList = array();
    private $_count = 0;
    
    public function __construct(array $collection = array())
    {

        $this->_innerList = $collection;
        $this->_count = count($this->_innerList);
    }

    public function count()
    {
        $this->_count = count($this->_innerList);
        return $this->_count;
    }

    public function items($index)
    {
        return $this->_innerList[$index];
    }

    public function add($object)
    {
        $index = count($this->_innerList);
        $this->_innerList[$index] = $object;
    }

    public function insert($object, $index)
    {
        $current = array();
        $current[0] = $object;
        $current[1] = $this->_innerList[$index];

        array_splice($this->_innerList, $index, $current);
    }

    public function update($object, $index)
    {
        $this->_innerList[$index] = $object;
    }

    public function removeAt($index)
    {
        unset($this->_innerList[$index]);
    }

    public function contains($object)
    {
        $result = array_search($object, $this->_innerList);

        return $result;
    }

    public function clear()
    {
        for($i = 0; $i < $this->_count; $i++) {
            unset($this->_innerList[$i]);
        }
    }

    public function toArray()
    {
        return $this->_innerList;
    }

    public function toString()
    {
        $result = '';
        for($i = 0; $i < $this->count(); $i++) {
            $result .= $this->_innerList[$i] . "\n";
        }

        return $result;
    }
}
