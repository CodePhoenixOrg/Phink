<?php
namespace Phink\Text;



/**
 * Description of match
 *
 * @author david
 */
class TRegexMatch extends \Phink\Core\TObject
{
    //put your code here
    private $_text = '';
    private $_groups = NULL;
    private $_position = 0;
    private $_tmpText = '';

    public function  __construct($text, $groups, $position)
    {
        $this->_text = $text;
        $this->_tmpText = $text;
        $this->_groups = $groups;
        $this->_position = $position;

    }

    public function getText()
    {
        return $this->_text;
    }

    public function getGroups()
    {
        return $this->_groups;
    }

    public function getPosition()
    {
        return $this->_position;
    }

    public function replaceGroup($groupId, $exp)
    {
        $this->_tmpText = str_replace($this->_groups[$groupId], $exp, $this->_tmpText);
        return $this->_tmpText;
    }


}
?>
