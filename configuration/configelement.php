<?php
namespace Phoenix\Configuration;

//require_once 'phoenix/core/aobject.php';

use Phoenix\Core\TObject;
/**
 * Description of ahtmlelement
 *
 * @author david
 */
class TConfigElement extends TObject
{

    private $_id = '';
    private $_name = '';
    private $_value = '';
    private $_type = '';

    public function __construct($id, $name, $value)
    {
        $this->_id = $id;
        $this->_name = $name;
        $this->_value = $value;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getValue()
    {
        return $this->_value;
    }

    public function getType()
    {
        return $this->_type;
    }


}
