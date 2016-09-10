<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Phink\Web\UI;

/**
 * Description of custom_control
 *
 * @author David
 */
abstract class TCustomControl extends \Phink\Core\TObject
{
    use \Phink\Web\TWebObject;

    //put your code here
    protected $isRendered = false;

    public function init() {}
   
    public function load() {}
    
    public function partialLoad() {}
    
    public function beforeBinding() {}

    public function parse() {}

    public function renderHtml() {}

    public function render() {}

    public function unload() {}
    
}
