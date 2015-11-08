<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Phoenix\Web\UI\Algo;

interface IAlgo
{
    function render();
}

trait TAlgo
{
    use \Phoenix\Data\UI\TDataBinder;
    
    protected $elements = null;
    
    public function getElements()
    {
        return $this->elements;
    }
    public function setElements($value)
    {
        $this->elements = $value;
    }
    
}