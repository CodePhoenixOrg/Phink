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
 
 namespace Phink\Configuration;

//require_once 'phink/core/aobject.php';

use Phink\Core\TObject;
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
