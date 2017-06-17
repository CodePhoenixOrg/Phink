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
 
 
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Phink\Web\UI\Plugin;
/**
 * Description of newPHPClass
 *
 * @author Akades
 */
class TUlli extends TCustomPlugin
{
    //put your code here
    public function render()
    {
        
        $noTHead = false; 
        $elements = $this->elements;
        $css = $this->css ? ' class="' . $this->css . '"' : '';
        
        $result = "\n";
        $tbody = str_replace('%s', $css, $elements[0]->getOpening()) . "\n";
        $body = $this->data['values'];
        $oldValue = [];
        $oldValue[0] = '&nbsp;';
        for($i = 0; $i < $this->rows; $i++) {
            $css = '';

            $row = (isset($body[$i])) ? json_decode($body[$i]) : array_fill(0, $this->columns, '&nbsp;');
            $typeId0 = 'id="' . $this->getId() .  $elements[1]->getType() . ($i) . '"';
            $tbody .= str_replace('%s', $typeId0 . $css, $elements[1]->getOpening()) . "\n";
            for($j = 0; $j < $this->columns; $j++) {
                $k = $i * $this->columns + $j;
                $noTHead = $this->templates[$j]['content'] && $this->templates[$j]['enabled'] == 1;
                $html = \Phink\Web\UI\Widget\Plugin\TPlugin::applyTemplate($this->templates, $row, $j);
                
                if($this->templates[$j]['enabled'] == 1 && $row[$j] != $oldValue[$j]) {
                    $tbody .= str_replace('%s', '', $elements[2]->getOpening()) . $html . $elements[2]->getClosing() . "\n";
                }
                $oldValue[$j] = $row[$j];
            }
            $tbody .= $elements[1]->getClosing() . "\n";
        }
        $tbody .= $elements[0]->getClosing() . "\n";

        $result .= $tbody;
        
        return $result;
    }
}