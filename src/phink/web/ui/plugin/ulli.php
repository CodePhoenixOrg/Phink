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
 
namespace Phink\Web\UI\Plugin;

use \Phink\Web\UI\Widget\Plugin\TPlugin;

/**
 * Description of newPHPClass
 *
 * @author David
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
        $names = $this->data['names'];
        $oldValue = array_fill(0, count($this->rows), '&nbsp;');
        for ($i = 0; $i < $this->rows; $i++) {
            $css = '';

            $row = (isset($body[$i])) ? json_decode($body[$i]) : array_fill(0, $this->columns, '&nbsp;');
            $typeId0 = 'id="' . $this->getId() .  $elements[1]->getType() . ($i) . '"';
            $tbody .= str_replace('%s', $typeId0 . $css, $elements[1]->getOpening()) . "\n";
            for ($j = 0; $j < $this->columns; $j++) {
                $k = $i * $this->columns + $j;
                
                $dataIndex = array_keys($names, $this->templates[$j]['name'])[0];
                $noTHead = !empty($this->templates[$j]['content']) && $this->templates[$j]['enabled'] == 1;

                $html = $row[$dataIndex];
                if ($noTHead) {
                    $html = TPlugin::applyTemplate($this->templates, $names, $row, $j);
                }
                if (isset($row[$j]) && isset($this->templates[$j]) && isset($oldValue[$j])) {
                    if ($this->templates[$j]['enabled'] == 1 && $row[$j] != $oldValue[$j]) {
                        $tbody .= str_replace('%s', '', $elements[2]->getOpening()) . $html . $elements[2]->getClosing() . "\n";
                    }
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
