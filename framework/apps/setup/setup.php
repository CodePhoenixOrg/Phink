<?php

namespace Phink;

use Phink\JavaScript\PhinkBuilder;
use Phink\Utils\TZip;
use Phink\Web\TCurl;

class Setup
{

    private $_rewriteBase = '/';

    public static function create(): Setup
    {
        return new Setup();
    }

    public function __construct()
    {
        $this->_rewriteBase =  pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR;
    }

    public function getRewriteBase(): string
    {
        return $this->_rewriteBase;
    }

    public function installPhinkJS(): bool
    {
        $filename = 'phinkjs.tar.gz';
        $tarfilename = 'phinkjs.tar';

        $filepath = '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'framework'  . DIRECTORY_SEPARATOR;


        if (file_exists($tarfilename)) {
            unlink($tarfilename);
        }

        $curl = new TCurl();
        $result = $curl->request('https://github.com/CodePhoenixOrg/PhinkJS/archive/master.tar.gz');
        file_put_contents($filename, $result->content);

        $p = new \PharData($filename);
        $p->decompress(); // creates files.tar

        unlink($filename);

        // unarchive from the tar
        $phar = new \PharData($tarfilename);
        $phar->extractTo($filepath);

        chdir($filepath);

        $ok = rename('PhinkJS-master', 'phinkjs');

        unlink($tarfilename);

        return $ok;
    }

    public function fixRewritBase(): bool
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

                $ok = false !== file_put_contents('.htaccess', $htaccess);
                $ok = $ok && false !== file_put_contents('..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'rewrite_base', $this->_rewriteBase);

                return $ok;
            }
        }
    }

    public function makeIndex(): bool
    {
        $index = <<<INDEX
        <?php
        include 'bootstrap.php';
        
        Phink\Web\TWebApplication::create();
    
        INDEX;

        return false !== file_put_contents('index.php', $index);
    }
}
