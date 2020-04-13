<?php
namespace Phink\Apps\Console;

use Phink\MVC\TPartialController;
use Phink\Crypto\TCrypto;

/**
 * Description of logme
 *
 * @author david
 */

class TToken extends TPartialController
{
    protected $label = '';
    protected $token = '';

    public function beforeBinding(): void
    {
        $this->showToken();
    }

    public function setLabel(string $value): void
    {
        $this->label = $value;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function showToken(string $label = 'token'): void
    {
        $this->token = TCrypto::generateToken();
        $this->label = $label;
    }
}
