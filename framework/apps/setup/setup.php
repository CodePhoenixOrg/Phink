<?php

namespace Phink;

use Phink\JavaScript\PhinkBuilder;

class Setup
{

    private $_rewriteBase = '/';

    public static function run(): void
    {
        new Setup();
    }

    public function __construct()
    {
        $this->_rewriteBase =  pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR;

        $this->_fixRewritBase();
        $this->_makeIndex();

        header('Location: ' . $this->_rewriteBase);
    }

    private function _fixRewritBase(): void
    {
        if (($htaccess = file_get_contents('.htaccess')) && file_exists('bootstrap.php')) {
            $htaccess = str_replace(PHP_EOL, ';', $htaccess);
            $text = strtolower($htaccess);

            $ps = strpos($text, 'rewritebase');
            if ($ps > -1) {
                $pe = strpos($htaccess, ' ', $ps);
                $rewriteBaseKey = substr($htaccess, $ps, $pe - $ps);
                $pe = strpos($htaccess, ';', $ps);
                $rewriteBaseEntry = substr($htaccess, $ps, $pe - $ps);

                $htaccess = str_replace($rewriteBaseEntry, $rewriteBaseKey . ' ' . $this->_rewriteBase, $htaccess);
                $htaccess = str_replace(';', PHP_EOL, $htaccess);

                file_put_contents('.htaccess', $htaccess);
                file_put_contents('..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'rewrite_base', $this->_rewriteBase);
            }
        }
    }

    private function _makeIndex(): void
    {
        $index = <<<INDEX
        <?php
        include 'bootstrap.php';
        
        Phink\Web\TWebApplication::create();
    
        INDEX;

        file_put_contents('index.php', $index);
    }
}
