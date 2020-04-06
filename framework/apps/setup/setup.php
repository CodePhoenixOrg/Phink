<?php

namespace Phink;

use Phink\Log\TLog;
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
        $ok = false;

        try {

            $filename = 'phinkjs.tar.gz';
            $tarfilename = 'phinkjs.tar';

            $filepath = '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'framework'  . DIRECTORY_SEPARATOR;

            if (file_exists($tarfilename)) {
                unlink($tarfilename);
            }

            $curl = new TCurl();
            $result = $curl->request('https://github.com/CodePhoenixOrg/PhinkJS/archive/master.tar.gz');
            $ok = false !== file_put_contents($filename, $result->content);

            $p = new \PharData($filename);
            $p->decompress();

            if (file_exists($filename)) {
                unlink($filename);
            }

            $phar = new \PharData($tarfilename);
            $phar->extractTo($filepath);

            if (file_exists($tarfilename)) {
                unlink($tarfilename);
            }

            chdir($filepath);

            $ok = $ok && rename('PhinkJS-master', 'phinkjs');
        } catch (\Exception $ex) {
            $ok = false;
            $log = TLog::create();
            $log->error($ex);
        }

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

    public function makeBootstrap(): bool
    {
        $bootstrap1 = <<<BOOTSTRAP1
<?php
include '../../framework/phink/phink_library.php';
include '../../framework/plugins/plugins_library.php';
        
BOOTSTRAP1;

        $bootstrap2 = <<<BOOTSTRAP2
<?php
if (
    false !== realpath(rtrim(\$_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . (\$path = substr(\$_SERVER['REQUEST_URI'], 0, -9)) . DIRECTORY_SEPARATOR . 'index.php')
    && \$path == (\$upath = parse_url(\$_SERVER['REQUEST_URI'], PHP_URL_PATH))
) {
    header('Location: ' . \$path);
    exit(301);
}
\$framework_dir = \$_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR;
include \$framework_dir . 'phink/phink_library.php';
include \$framework_dir . 'plugins/plugins_library.php';

BOOTSTRAP2;
        // include '../../vendor/autoload.php';

        $bootstrap = $bootstrap1;
        if(strpos($_SERVER['SERVER_SOFTWARE'], 'embedded') > -1) {
            $bootstrap = $bootstrap2;
        }

        return false !== file_put_contents('bootstrap.php', $bootstrap);
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
