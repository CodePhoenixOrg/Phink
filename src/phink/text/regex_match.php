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
