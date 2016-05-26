<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class THtmlTemplate extends \Phink\Core\TObject 
    implements \JsonSerializable
{
    use \Phink\Web\UI\THtmlControl;
    
    public function jsonSerialize() {
        return serialize($this);
    }
}
