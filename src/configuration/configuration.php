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
 
 namespace Phink\Configuration;

/**
 * Description of aconfig
 *
 * @author david
 */
abstract class TConfiguration extends \Phink\Core\TObject
{
    private $_innerList = array();
    
    public function __construct($parent)
    {
        parent::__construct($parent);
    }

    public function loadFromFile($filename)
    {
        if(!file_exists($filename)) {
            return false;
        }
        
        $text = file_get_contents($filename);
        $text = str_replace("\r", '', $text);
        $lines = explode("\n", $text);
        
        foreach($lines as $line) {
            array_push($this->_innerList, $line);
        }
    }
    
    public function readLine()
    {
        $result = each($this->_innerList);
        if(!$result) reset($this->_innerList);
        return $result;
    }
}