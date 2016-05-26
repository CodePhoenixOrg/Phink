<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Phink\Data\UI;

trait TDataBinder 
{
    protected $columns = 0;
    protected $rows = 0;
    protected $data = array();
    protected $templates = array();
    protected $pivot = false;

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
        $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        $this->pivot = $value;
    }
    
    public static function applyTemplate($templates, $row, $j) 
    {
        $cols = count($row);
        $html = $row[$j];
        
        if($templates[$j]['content'] && $templates[$j]['enabled'] == 1) {
            $html = $templates[$j]['content'];
            $event = $templates[$j]['event'];
            $e = explode('#', $event);
            if($e[0] == 'href') {
                $event = 'javascript:' . $e[1];
            } else {    
                $event = $e[0] . '="' . $e[1] . '"'; 
            }
            for ($m = 0; $m < $cols; $m++) {
                $head = $templates[$m]['name'];
                $html = str_replace('<% ' . $head . ' %>', $row[$m], $html);
                $html = str_replace('<% ' . $head . ':index %>', $m, $html);
                $event = str_replace($head, "'" . $row[$m] . "'", $event);
                $html = str_replace('<% &' . $head . ' %>', $event, $html);
                
            }
            
            
        }
        return $html;
    }
    
    public static function applyDragHelper($templates, $row, $j) 
    {
        $cols = count($row);
        $html = $row[$j];
        
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
                $html = str_replace('<% ' . $head . ' %>', $row[$m], $html);
                $html = str_replace('<% ' . $head . ':index %>', $m, $html);
                $event = str_replace($head, "'" . $row[$m] . "'", $event);
                $html = str_replace('<% &' . $head . ' %>', $event, $html);
            }
            
            
        }
        return $html;
    }
}