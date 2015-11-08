<?php
namespace Phoenix\Web\UI;

/**
 * Description of ahtmlelement
 *
 * @author david
 */
class THtmlElement extends \Phoenix\Core\TObject
{

    private $_id = '';
    private $_rule = '';
    private $_pattern = '';
    private $_opening = '';
    private $_closing = '';
    private $_type = '';

    public function  __construct($id, $pattern, $rule)
    {
        $this->_id = $id;
        $this->_pattern = $pattern;
        $this->_rule = $rule;

        $tag = explode('>{} <', $pattern);
        $this->_opening = $tag[0] . '>';
        if(count($tag) == 1) {
            $this->_closing = " />";
        }
        else {
            $this->_closing = '<' . $tag[1];
        }

        $this->_type = \Phoenix\Utils\TStringUtils::elementType($this->_opening);
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getPattern()
    {
        return $this->_pattern;
    }

    public function getRule()
    {
        return $this->_rule;
    }

    public function getOpening()
    {
        return $this->_opening;
    }
    
    public function getClosing()
    {
        return $this->_closing;
    }

    public function getType()
    {
        return $this->_type;
    }
}

