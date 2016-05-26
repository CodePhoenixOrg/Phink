<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Phink\Data\UI;

/**
 * Description of select
 *
 * @author David
 */
class TSelect extends \Phink\Core\TObject
{
    //put your code here
    
    private $_data = null;
    
    public function getData()
    {
        return $this->_data;
    }
    
    public function setData($value)
    {
        $this->_data = $value;
    }
}
