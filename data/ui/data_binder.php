<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Phoenix\Data\UI;

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
    
    public function applyTemplate($template, $cols, $row, $head, $j) 
    {
        $html = $row[$j];
        if($template['content'] && $template['enabled'] == 1) {
            $html = $template['content'];
            $event = $template['event'];
            $e = explode('#', $event);
            if($e[0] == 'href') {
                $event = 'javascript:' . $e[1];
            } else {    
                $event = $e[0] . '="' . $e[1] . '"'; 
            }
            for ($m = 0; $m < $cols; $m++) {
                $html = str_replace('<% ' . $head[$m] . ' %>', $row[$m], $html);
                //\Phoenix\Log\TLog::dump('HTML1::', $html);
                $event = str_replace($head[$m], $row[$m], $event);
                $html = str_replace('<% &' . $head[$m] . ' %>', $event, $html);
                //\Phoenix\Log\TLog::dump('HTML2::', $html);
            }
            
            
        }
        return $html;
    }
    
}