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
 
 namespace Phink\Configuration\Data;

//require_once 'phink/configuration/TConfiguration.php';

use Phink\Configuration\TConfiguration;

/**
 * Description of TFileConfiguration
 *
 * @author david
 */
class TFileConfiguration extends TConfiguration
{
    //put your code here
    protected $innerList = [];
    
    public function configure() : void
    {
        $this->innerList = file($this->filename);
    }

    public function readLine() : string
    {
        $result = each($this->innerList);
        if (!$result) {
            reset($this->innerList);
        }

        return $result;
    }
}
