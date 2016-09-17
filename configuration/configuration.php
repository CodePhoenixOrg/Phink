<?php
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
