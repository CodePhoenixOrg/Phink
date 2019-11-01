<?php
namespace Phink\Apps\Console;

use Phink\MVC\TPartialController;
use Phink\MVC\TActionInfo;
use Phink\Registry\TRegistry;

/**
 * Description of dummy
 *
 * @author david
 */

class TConsoleWindow extends TPartialController
{
    protected $text = "Unknown command";
    protected $args = '';
    protected $cookies = '';
    protected $consoleName = '';
    protected $themeBackColor = '';
    protected $themeForeColor = '';

    public function afterBinding(): void
    {
        $this->consoleName = $this->getApplication()->getName();
        $this->cookies = $this->getApplication()->getCookie($this->consoleName);

        $cmd = isset($this->parameters['console']) ? $this->parameters['console'] : '';
        $arg = isset($this->parameters['arg']) ? $this->parameters['arg'] : null;

        // $this->setArgs($this->parameters);

        $this->commandRunner($cmd, function ($data) {
            $this->setText($data);
        }, $arg);
    }

    public function setArgs($value) : void
    {
        $this->args = $value;
        if (is_array($value)) {
            $this->args = print_r($value, true);
        }
    }

    public function getArgs() : array
    {
        return $this->args;
    }
    
    public function setText($value) : void
    {
        $this->text = $value;
        if (is_array($value)) {
            $this->text = print_r($value, true);
        }
    }
    
    public function getText() : string
    {
        return $this->text;
    }

    public function commandRunner(string $cmd, callable $callback, ...$args) : void
    {
        if (isset($this->commands[$cmd])) {
            $cmd = $this->commands[$cmd];
            $statement = $cmd['callback'];

            if ($statement !== null && count($args) === 0) {
                call_user_func($statement, $callback);
            } elseif ($statement !== null && count($args) > 0) {
                call_user_func($statement, $callback, $args);
            }
        }
    }

    public function clearLogs() : TActionInfo
    {
        $data = $this->getApplication()->clearLogs();

        return TActionInfo::set($this, 'result', $data);
    }

    public function clearRuntime() : TActionInfo
    {
        $data = $this->getApplication()->clearRuntime();

        return TActionInfo::set($this, 'result', $data);
    }

    public function displayDebugLog() : TActionInfo
    {
        $data = $this->getApplication()->getDebugLog();

        return TActionInfo::set($this, 'result', $data);
    }

    public function displayPhpErrorLog() : TActionInfo
    {
        $data = $this->getApplication()->getPhpErrorLog();

        return TActionInfo::set($this, 'result', $data);
    }

    public function setTheme(string $theme) : TActionInfo
    {
        //setcookie($this->getApplication()->getName() . '[theme]', $theme, time() - 3600);
        setcookie($this->getApplication()->getName() . '[theme]', $theme, time() + 3600, '/', HTTP_HOST, true, true);
    
        $themeBackColor = TRegistry::ini('theme_' . $theme, 'back_color');
        $themeForeColor = TRegistry::ini('theme_' . $theme, 'fore_color');

        return TActionInfo::set($this, 'theme', ['name' => $theme, 'backColor' => $themeBackColor, 'foreColor' => $themeForeColor]);
    }
}
