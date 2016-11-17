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
 
 namespace Phink\Data\UI;

/**
 * Description of adatatag
 *
 * @author david
 */
trait TDataTag {
    protected $command;
    protected $statement;

    protected function assocArrayByAttribute(array $array, $attribute)
    {
        $result = array();

        $c = count($array);
        for ($i = 0; $i < $c; $i++) {
            $method = 'get' . ucfirst($attribute);
            $object = $array[$i];
            $intermediate = $object->$method();
            $result[$intermediate] = $object;
        }

        return $result;
    }    
    
    protected function getControls(array $objectList)
    {
        $result = array();
        $c = count($objectList);
        for($i = 0; $i < $c; $i++) {
            $object = $objectList[$i];
            $template = $object->getProperties();
            array_push($result, $template);
        }
        
        return $result;
    }

    public function getCommand()
    {
        return $this->command;
    }
    public function setCommand(\Phink\Data\Client\PDO\TPdoCommand $value)
    {
        $this->command = $value;
    }

    public function getStatement()
    {
        return $this->statement;
    }
    public function setStatement(\Phink\Data\Client\PDO\TPdoDataStatement $value)
    {
        $this->statement = $value;
    }
//    public function getReader()
//    {
//        $reader = NULL;
//        if(isset($this->_command)) {
//            $reader = $this->_command->getReader();
//            if(!isset($reader)) {
//                throw new Exception('DataReader must be set before, by executing the command.', '123', NULL);
//            }
//        }
//
//        return $reader;
//    }

    public function dataBind()
    {
        if(isset($this->command)) {
            $this->render();
        }
    }
}
