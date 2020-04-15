<?php
/*
 * Copyright (C) 2019 David Blanchard
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
 * Description of THtmlElement
 *
 * @author david
 */
class THtmlElement extends \Phink\Core\TObject
{

    private $_rule = '';
    private $_pattern = '';
    private $_opening = '';
    private $_closing = '';
    private $_type = '';

    public function  __construct($id, $pattern, $rule)
    {
        $this->id = $id;
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

        $this->_type = \Phink\Utils\TStringUtils::elementType($this->_opening);
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

    public function getType(): string
    {
        return $this->_type;
    }
}

