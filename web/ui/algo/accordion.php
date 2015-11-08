<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Phoenix\Web\UI\Algo;
/**
 * Description of newPHPClass
 *
 * @author Akades
 */
class TAccordion implements IAlgo
{
    use TAlgo;    
    //put your code here
    public function run()
    {
        $result = "\n";

        $elements = $this->elements;
        
        $tbody = '';
        $level = 0;
        $oldLevel = 0;
        $bound = array_fill(0, 3, false);
        $head = $this->data['names'];
        $body = $this->data['values'];
        $this->rows = count($body);
        $this->columns = count($head);
        $dataIndex = 0;
        $templateNum = count($this->templates);
        $oldValue = array_fill(0, $this->columns, '');
        
        for($k = 0; $k < $templateNum; $k++) {
            for($j = 0; $j < $this->columns; $j++) {
                if($this->templates[$k]['name'] == $this->data['names'][$j]) {
                    $this->templates[$k]['index'] = $j;
                }
            }
        }
                        
        for($i = 0; $i < $this->rows; $i++) {
            $row = (isset($body[$i])) ? json_decode($body[$i]) : array_fill(0, $this->columns, '&nbsp;');
            for($j = 0; $j < $templateNum; $j++) {
                 if($j == 0) {
                    $level = 0;
                }
                if($this->templates[$j]['enabled'] != 1) continue;
                $index = $this->templates[$j]['index'];
                $canBind = $row[$index] != $oldValue[$j];
                //$canBind = $canBind && $this->templates[$j]['name'] === $head[$dataIndex];
                //\Phoenix\Log\TLog::debug('TEMPLATE NAME : ' . $this->templates[$j]['name'] . '; HEAD NAME :' . $head[$dataIndex]);
                if(!$canBind) {
                    $bound[$level] = $canBind;
                    $oldLevel = $level;
                    $level++;
                    $oldValue[$j] = $row[$index];
                    continue;
                }
                //$html = $this->_applyTemplate($this->templates[$j], $this->columns, $row, $head, $j);
                $html = $row[$index];
                
                if($level === 0) {
                    if($i > 0) {
                        $tbody .= $elements[2]->getClosing() . "\n" . $elements[0]->getClosing() . "\n";
                        $tbody .= $elements[2]->getClosing() . "\n" . $elements[0]->getClosing() . "\n";
                    }
                    $tbody .= str_replace('%s', 'blue', $elements[0]->getOpening()) . "\n";
                    $tbody .= $elements[1]->getOpening() . $html . $elements[1]->getClosing() . "\n";
                    $tbody .= $elements[2]->getOpening();
                }
                elseif($level === 1) {
                    if($i > 0 && !$bound[$level - 1]) $tbody .= $elements[2]->getClosing() . "\n" . $elements[0]->getClosing() . "\n";
                    $tbody .= str_replace('%s', 'odd', $elements[0]->getOpening()) . "\n";
                    $tbody .= $elements[1]->getOpening() . $html . $elements[1]->getClosing() . "\n";
                    $tbody .= $elements[2]->getOpening();
                }
                elseif($level === 2) {
                    $tbody .= str_replace('%s', '', $elements[2]->getOpening()) . $html . $elements[2]->getClosing() . "\n";
                }                
                $bound[$level] = $canBind;
                $oldLevel = $level;
                $level++;
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
