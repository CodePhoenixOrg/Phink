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

namespace Phink\Plugins\Accordion;

use \Phink\Web\UI\Plugin\TCustomPlugin;
use \Phink\Web\UI\Widget\Plugin\TPlugin;

/**
 * Description of TAccordion
 *
 * @author David
 */
class TAccordion extends TCustomPlugin
{
    public function render()
    {
        $result = "\n";

        $elements = $this->elements;

        $tbody = '';
        $level = 0;
        $boundIndex = 0;
        $oldLevel = 0;
        $bound = array_fill(0, 3, false);
        $names = $this->data['names'];
        $body = $this->data['values'];
        $this->rows = count($body);
        $this->columns = count($names);
        $dataIndex = 0;
        $templateNum = count($this->templates);
        $oldValue = array_fill(0, $this->columns, '');
        $bindablesCount = 0;
        $firstBindableIndex = -1;
        $lastBindableIndex = -1;

        for ($k = 0; $k < $templateNum; $k++) {
            for ($j = 0; $j < $this->columns; $j++) {
                if ($this->templates[$k]['name'] == $this->data['names'][$j]) {
                    $this->templates[$k]['index'] = $j;
                    if ($this->templates[$k]['enabled'] == 1) {
                        $bindablesCount++;
                        if ($firstBindableIndex == -1) {
                            $firstBindableIndex = $j;
                        }

                        $lastBindableIndex = $j;
                    }
                }
            }
        }

        for ($i = 0; $i < $this->rows; $i++) {
            $row = (isset($body[$i])) ? $body[$i] : array_fill(0, $this->columns, '&nbsp;');
            for ($j = 0; $j < $templateNum; $j++) {
                if ($j == 0) {
                    $level = 0;
                    $boundIndex = 0;
                }
                if ($this->templates[$j]['enabled'] != 1) {
                    continue;
                }

                $index = $this->templates[$j]['index'];
                $canBind = $row[$index] != $oldValue[$j];
                if (!$canBind) {
                    $bound[$boundIndex] = $canBind;
                    $oldLevel = $level;
                    $oldValue[$j] = $row[$index];
                    $boundIndex++;
                    continue;
                }
                $level = (($index == $firstBindableIndex) ? 0 : (($index == $lastBindableIndex) ? 2 : 1));

                $dataIndex = array_keys($names, $this->templates[$j]['name'])[0];
                $noTHead = !empty($this->templates[$j]['content']) && $this->templates[$j]['enabled'] == 1;

                $html = $row[$dataIndex];
                if ($noTHead) {
                    $html = TPlugin::applyTemplate($this->templates, $names, $row, $j);
                }

                if ($level === 0) {
                    if ($i > 0) {
                        for ($l = 1; $l < $bindablesCount; $l++) {
                            $tbody .= $elements[2]->getClosing() . "\n" . $elements[0]->getClosing() . "\n";
                        }
                        $oldValue = array_fill(0, $this->columns, '!#');
                    }
                    $tbody .= str_replace('%s', 'blue', $elements[0]->getOpening()) . "\n";
                    $tbody .= $elements[1]->getOpening() . $html . $elements[1]->getClosing() . "\n";
                    $tbody .= $elements[2]->getOpening();
                } elseif ($level === 1) { //&& $level < $lastBindableIndex
                    if ($i > 0 && !$bound[$boundIndex - 1]) {
                        $tbody .= $elements[2]->getClosing() . "\n" . $elements[0]->getClosing() . "\n";
                    }
                    $tbody .= str_replace('%s', 'odd', $elements[0]->getOpening()) . "\n";
                    $tbody .= $elements[1]->getOpening() . $html . $elements[1]->getClosing() . "\n";
                    $tbody .= $elements[2]->getOpening();
                } elseif ($level === 2) {
                    $tbody .= str_replace('%s', '', $elements[2]->getOpening()) . $html . $elements[2]->getClosing() . "\n";
                }
                $bound[$boundIndex] = $canBind;
                $oldLevel = $level;
                $boundIndex++;
                $oldValue[$j] = $row[$index];
            }
        }
        $tbody .= $elements[2]->getClosing() . "\n";
        $tbody .= $elements[0]->getClosing() . "\n";
        $tbody .= $elements[2]->getClosing() . "\n";
        $tbody .= $elements[0]->getClosing() . "\n";

        $result .= $tbody;

        return $result;
    }
}
