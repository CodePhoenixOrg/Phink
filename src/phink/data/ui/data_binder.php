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
 
namespace Phink\Data\UI;

trait TDataBinder 
{
    protected $columns = 0;
    protected $rows = 0;
    protected $data = array();
    protected $templates = array();
    protected $pivot = false;
    protected $tileBy = false;

    public function getTiled()
    {
        return $this->tileBy;
    }
    public function setTiled($value)
    {
        if($value === 'auto') {
            $this->tileBy = -1;
        } else {
            
        }
        $this->tileBy = filter_var($value, FILTER_VALIDATE_INT);
    }

    public function setTemplates($value)
    {
        $this->templates = $value;
    }

    public function getCols()
    {
        return $this->columns;
    }
    public function setCols($value)
    {
        $this->columns = $value;
    }

    public function getRows()
    {
        return $this->rows;
    }
    public function setRows($value)
    {
        $this->rows = $value;
    }

    public function setData($value)
    {
        $this->data = $value;
    }
    
    public function getPivot()
    {
        return $this->pivot;
    }
    public function setPivot($value)
    {
        $this->pivot = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
    
    public static function applyTemplate($templates, $names, $values, $templateIndex) 
    {
        self::$logger->debug('TEMPLATE : ' . print_r($templates[$templateIndex], true));

        $cols = count($values);

        $html = $templates[$templateIndex]['content'];
        $event = $templates[$templateIndex]['event'];
        $e = explode('#', $event);
        if (count($e) > 1) {
            if ($e[0] == 'href') {
                $event = 'javascript:' . $e[1];
            } else {
                $event = $e[0] . '="' . $e[1] . '"';
            }
        } else {
            $event = $e[0];
        }
        for ($m = 0; $m < $cols; $m++) {
            $head = $templates[$m]['name'];
            $i = array_keys($names, $head)[0];

            //self::$logger->debug('HEAD : ' . $head);
            //self::$logger->debug('NAMES : ' . $names);

            $html = str_replace('<% ' . $head . ' %>', $values[$i], $html);
            $html = str_replace('<% ' . $head . ':index %>', $i, $html);
            $event = str_replace($head, "'" . $values[$i] . "'", $event);
            $html = str_replace('<% &' . $head . ' %>', $event, $html);

        }

        return $html;
    }
    
    public static function applyDragHelper($templates, $values, $j) 
    {
        $cols = count($values);
        $html = $values[$j];
        
        if($templates[$j]['dragHelper'] && $templates[$j]['enabled'] == 1) {
            $html = $templates[$j]['dragHelper'];
            $event = $templates[$j]['event'];
            $e = explode('#', $event);
            if($e[0] == 'href') {
                $event = 'javascript:' . $e[1];
            } else {    
                $event = $e[0] . '="' . $e[1] . '"'; 
            }
            for ($m = 0; $m < $cols; $m++) {
                $head = $templates[$m]['name'];
                $html = str_replace('<% ' . $head . ' %>', $values[$m], $html);
                $html = str_replace('<% ' . $head . ':index %>', $m, $html);
                $event = str_replace($head, "'" . $values[$m] . "'", $event);
                $html = str_replace('<% &' . $head . ' %>', $event, $html);
            }
            
            
        }
        return $html;
    }
}