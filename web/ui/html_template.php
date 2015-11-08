<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class THtmlTemplate extends \Phoenix\Core\TObject 
    implements \JsonSerializable
{
    use \Phoenix\Web\UI\THtmlControl;
    
    public function jsonSerialize() {
        return serialize($this);
    }
}
