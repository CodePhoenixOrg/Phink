<?php

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
