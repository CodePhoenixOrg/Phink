<?php

namespace Phink\Web\UI\Widget\ConsoleWindow;

//require_once 'phink/mvc/partial_controller.php';

use Phink\MVC\TPartialController;

/**
 * Description of dummy
 *
 * @author david
 */


class TConsoleWindow extends TPartialController {

////put your code here
    protected $text = "Console Window";
    protected $anchor = '';

    public function setAnchor($value)
    {
        $this->anchor = $value;
    }
 
    public function afterBinding()
    {
        $cmd = $this->parameters['console'];
        $arg = isset($this->parameters['arg']) ? $this->parameters['arg'] : null;

        $this->commandRunner($cmd, function ($data) {
            $this->setText($data);

        }, $arg);
    }
    
    public function setText($value)
    {
        $this->text = $value;
        if(is_array($value)) {
            $this->text = print_r($value, true);
        }
    }
    
    public function getText()
    {
        return $this->text;
    }

    public function commandRunner(string $cmd, callable $callback, $arg = null) {

        if (isset($this->commands[$cmd])) {
            $cmd = $this->commands[$cmd];
            $statement = $cmd['callback'];

            if ($statement !== null && $arg === null) {
                call_user_func($statement, $callback);
            } elseif ($statement !== null && $arg !== null) {
                call_user_func($statement, $callback, $arg);
            }

        }
    }
    
}

