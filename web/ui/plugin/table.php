<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Phoenix\Web\UI\Plugin;
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
        // header 
        $i = 0;
        $thead = $elements[1]->getOpening() . "\n";
        $typeId0 = 'id="' . $this->getId() .  $elements[3]->getType() . ($i) . '"';
        $thead .= str_replace('%s', $typeId0, $elements[3]->getOpening()) . "\n";
        for($j = 0; $j < $this->columns; $j++) {
            $k = $i * $this->columns + $j;
            $typeId1 = 'id="' . $this->getId() .  $elements[4]->getType() . $k . '"';
            $thead .= str_replace('%s', $typeId1, $elements[4]->getOpening()) . $head[$j] . $elements[4]->getClosing() . "\n";

        }
        $thead .= $elements[3]->getClosing() . "\n";
        $thead .= $elements[1]->getClosing() . "\n";

        if(!$this->pivot) {
            // values 
            $tbody = $elements[2]->getOpening() . "\n";
            $body = $this->data['values'];
            for($i = 0; $i < $this->rows; $i++) {

                $row = (isset($body[$i])) ? json_decode($body[$i]) : array_fill(0, $this->columns, '&nbsp;');
                $typeId0 = 'id="' . $this->getId() .  $elements[3]->getType() . ($i) . '"';
                $tbody .= str_replace('%s', $typeId0, $elements[3]->getOpening()) . "\n";
                for($j = 0; $j < $this->columns; $j++) {
                    $k = $i * $this->columns + $j;
                    $noTHead = $this->templates[$j]['content'] && $this->templates[$j]['enabled'] == 1;
                    $html = \Phoenix\Web\UI\Widget\Plugin\TPlugin::applyTemplate($this->templates, $row, $j);
                    $typeId1 = 'id="' . $this->getId() .  $elements[5]->getType() . $k . '"';
                    if($this->templates[$j]['enabled'] == 1) {
                        $tbody .= str_replace('%s', $typeId1, $elements[5]->getOpening()) . $html . $elements[5]->getClosing() . "\n";
                    }
                }
                $tbody .= $elements[3]->getClosing() . "\n";
            }
            $tbody .= $elements[2]->getClosing() . "\n";

            $result .= (($noTHead) ? '' : $thead) . $tbody;
        } else {
            // values 
            $tbody = $elements[2]->getOpening() . "\n";
            $body = $this->data['values'];
            $oldValue = array();
            for($i = 0; $i < $this->rows; $i++) {

                $row = (isset($body[$i])) ? json_decode($body[$i]) : array_fill(0, $this->columns, '&nbsp;');
                $typeId0 = 'id="' . $this->getId() .  $elements[3]->getType() . ($i) . '"';
                $tbody .= str_replace('%s', $typeId0, $elements[3]->getOpening()) . "\n";
                for($j = 0; $j < $this->columns; $j++) {
                    $k = $i * $this->columns + $j;
                    $noTHead = $this->templates[$j]['content'] && $this->templates[$j]['enabled'] == 1;
                    $html = \Phoenix\Web\UI\Widget\Plugin\TPlugin::applyTemplate($this->templates, $row, $j);
                    $typeId1 = 'id="' . $this->getId() .  $elements[5]->getType() . $k . '"';
                    if($this->templates[$j]['enabled'] == 1 && $row[$j] != $oldValue[$j]) {
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
