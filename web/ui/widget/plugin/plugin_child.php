<?php
namespace Phink\Web\UI\Widget\Plugin;

/**
 * Description of adatacolumn
 *
 * @author david
 */
class TPluginChild extends \Phink\Core\TObject
{
    use \Phink\Web\UI\THtmlControl;
    
    private $_value;

    public function getValue()
    {
        return $this->_value;
    }
    public function setValue($value)
    {
        $this->_value = $value;
    }

    public function data()
    {
        return $this->_value;
    }

}

