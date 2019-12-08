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
namespace Phink\Plugins\Table;

/**
 * Description of newPHPClass
 *
 * @author Akades
 */
class TTable extends TCustomPlugin
{
    //put your code here
    public function render()
    {

        $elements = $this->elements;
        $noTHead = false;

        $result = "\n";

        $head = $this->data['names'];

        $result .= str_replace('%s', 'id="' . $this->getId() . $elements[0]->getType() . '" class="table table-striped table-hover table-condensed"', $elements[0]->getOpening()) . "\n";
        /**
         * header
         */
        $thead = $elements[1]->getOpening() . "\n";
        $typeId0 = 'id="' . $this->getId() . $elements[3]->getType() . (0) . '"';
        $thead .= str_replace('%s', $typeId0, $elements[3]->getOpening()) . "\n";
        for ($j = 0; $j < $this->columns; $j++) {
            $colName = $head[$j];
            if($this->templates[0]['name'] != '*') {
                $colName = $this->templates[$j]['name'];
            }
            $colIndex = array_keys($head, $colName)[0];
            $typeId1 = 'id="' . $this->getId() . $elements[4]->getType() . $j . '"';
            $thead .= str_replace('%s', $typeId1, $elements[4]->getOpening()) . $head[$colIndex] . $elements[4]->getClosing() . "\n";
        }

        $thead .= $elements[3]->getClosing() . "\n";
        $thead .= $elements[1]->getClosing() . "\n";

        if (!$this->pivot) {
            /**
             * values
             */

            $tbody = $elements[2]->getOpening() . "\n";
            $body = $this->data['values'];
            for ($i = 0; $i < $this->rows; $i++) {

                $row = (isset($body[$i])) ? $body[$i] : array_fill(0, $this->columns, '&nbsp;');
                $typeId0 = 'id="' . $this->getId() . $elements[3]->getType() . ($i) . '"';
                $tbody .= str_replace('%s', $typeId0, $elements[3]->getOpening()) . "\n";

                for ($j = 0; $j < $this->columns; $j++) {
                    $k = $i * $this->columns + $j;
                    $colIndex = 0;
                    $dataIndex = array_keys($head, $head[$j])[0];
                    if($this->templates[0]['name'] != '*') {
                        $dataIndex = array_keys($head, $this->templates[$j]['name'])[0];
                        $colIndex = $dataIndex;
                    }

                    $noTHead = isset($this->templates[$j]) && !empty($this->templates[$j]['content']) && $this->templates[$j]['enabled'] == 1;

                    $html = $row[$dataIndex];
                    if ($noTHead) {
                        $html = \Phink\Web\UI\Widget\Plugin\TPlugin::applyTemplate($this->templates, $head, $row, $j);
                    }
                    if (isset($this->templates[$colIndex]) && $this->templates[$colIndex]['enabled'] == 1) {
                        $typeId1 = 'id="' . $this->getId() . $elements[5]->getType() . $k . '"';
                        $tbody .= str_replace('%s', $typeId1, $elements[5]->getOpening()) . $html . $elements[5]->getClosing() . "\n";
                    }
                }
                $tbody .= $elements[3]->getClosing() . "\n";
            }
            $tbody .= $elements[2]->getClosing() . "\n";

            $result .= (($noTHead) ? '' : $thead) . $tbody;
        }

        if ($this->pivot) {
            /**
             * values
             */

            $tbody = $elements[2]->getOpening() . "\n";
            $body = $this->data['values'];
            $oldValue = array();
            for ($i = 0; $i < $this->rows; $i++) {

                $row = (isset($body[$i])) ? json_decode($body[$i]) : array_fill(0, $this->columns, '&nbsp;');
                $typeId0 = 'id="' . $this->getId() . $elements[3]->getType() . ($i) . '"';
                $tbody .= str_replace('%s', $typeId0, $elements[3]->getOpening()) . "\n";
                for ($j = 0; $j < $this->columns; $j++) {
                    $k = $i * $this->columns + $j;
                    $noTHead = $this->templates[$j]['content'] && $this->templates[$j]['enabled'] == 1;
                    $html = \Phink\Web\UI\Widget\Plugin\TPlugin::applyTemplate($this->templates, $row, $j);
                    $typeId1 = 'id="' . $this->getId() . $elements[5]->getType() . $k . '"';
                    $name = $this->templates[$j]['name'];

                    if ($this->templates[$j]['enabled'] == 1 && $row[$j] != $oldValue[$j]) {

                        $tbody .= $elements[3]->getOpening();
                        $tbody .= str_replace('%s', $typeId1, $elements[5]->getOpening()) . $html . $elements[5]->getClosing() . "\n";
                        $tbody .= $elements[3]->getClosing();
                    }
                    $oldValue[$j] = $row[$j];
                }
                $tbody .= $elements[3]->getClosing() . "\n";
            }
            $tbody .= $elements[2]->getClosing() . "\n";

            $result .= (($noTHead) ? '' : $thead) . $tbody;
        }
        $result .= $elements[0]->getClosing() . "\n";

        return $result;

    }
}
