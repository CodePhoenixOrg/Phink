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
 
 
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Phink\Web\UI\Plugin;

abstract class TCustomPlugin extends \Phink\Core\TObject
{
    use \Phink\Data\UI\TDataBinder;
    use \Phink\Web\UI\THtmlControl;
    
    protected $elements = null;
    
    public function getElements()
    {
        return $this->elements;
    }
    public function setElements($value)
    {
        $this->elements = $value;
    }
    
    public function setTilesPerRow() {
        if($this->tileBy === -1) {
            $c = count($this->data['values']);
            $this->tileBy = (int) round((float)sqrt($c));
        }
        $this->tileBy = 6;
    }
    
    public abstract function render();
    
}