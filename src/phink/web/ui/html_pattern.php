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
 
 namespace Phink\Web\UI;

/**
 * Description of ahtmlpattern
 *
 * @author david
 */
trait THtmlPattern {
    //put your code here
    protected $elements = array();
    protected $pattern = '';

    public function getElements()
    {
        if(count($this->elements) == 0) {
            $this->_getElements();
        }
        return $this->elements;
    }
    public function setElements($value)
    {
        return $this->elements = $value;
    }

    public function getPattern()
    {
        return $this->pattern;
    }
    public function setPattern($value)
    {
        return $this->pattern = $value;
    }
    
    public function getPatternName()
    {
        return 'web' . DIRECTORY_SEPARATOR . 'ui' . DIRECTORY_SEPARATOR . 'html' . PATTERN_EXTENSION;
    }

    private function _getElements()
    {
        $type = $this->getType();
        $path = ROOT_PATH . \Phink\Core\TRegistry::classPath($type);
        
        $jsonName = RUNTIME_JS_DIR . str_replace(DIRECTORY_SEPARATOR, '_', $path . strtolower($type) . '.json');
        if(file_exists($jsonName)) {
            $json = file_get_contents($jsonName);
            $this->elements = unserialize($json);
            return true;
        }

        $patternName = $this->getPatternName();
        
        //self::$logger->dump('PATTERN NAME', $patternName);
        $contents = file_get_contents(PHINK_ROOT . $patternName, FILE_USE_INCLUDE_PATH);
        
        $doc = new \Phink\Xml\TXmlDocument($contents);
        $doc->matchAll();
        $elements = $doc->getList();

        foreach ($elements as $element) {
            
            $key = $element['properties']['id'];
            if(!isset($this->elements[$key])) {
                $this->elements[$key] = array();
            }
            
            array_push($this->elements[$key]
                , new THtmlElement($element['id']
                    , $element['properties']['pattern']
                    , $element['properties']['rule'])
                );
        }

        $jsonElements = serialize($this->elements);

        file_put_contents($jsonName, $jsonElements);
        

    }

}
