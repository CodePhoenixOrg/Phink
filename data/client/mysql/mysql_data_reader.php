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
 
 
namespace Phink\Data\Client\MySQL;

//require_once 'phink/data/data_reader.php';
//require_once 'phink/core/object.php';

use Phink\Core\TObject;
use Phink\Data\IDataReader;

/**
 * Description of adatareader
 *
 * @author david
 */
class TMySqlDataReader extends TObject implements IDataReader
{


    private $_result;
    private $_values;

    public function __construct($result)
    {
        $this->_result = $result;
    }

    public function values($i)
    {
        return $this->_values[$i];
    }

    public function read()
    {
        $this->_values = mysql_fetch_array($this->_result);
        if($this->_values > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
?>
