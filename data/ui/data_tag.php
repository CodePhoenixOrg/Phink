<?php
namespace Phoenix\Data\UI;

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
    public function setCommand(\Phoenix\Data\Client\PDO\TPdoCommand $value)
    {
        $this->command = $value;
    }

    public function getStatement()
    {
        return $this->statement;
    }
    public function setStatement(\Phoenix\Data\Client\PDO\TPdoDataStatement $value)
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
