<?php

namespace Phink;

use Phink\Core\PhpInfo;
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
        $this->_rewriteBase =  dirname(pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME)) . DIRECTORY_SEPARATOR;
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

    public function fixRewritBase(): ?string
    {
        $result = null;
        $ok = false;

        if ($ok = file_exists('bootstrap.php')) {

            $ok = $ok && false !== file_put_contents('..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'rewrite_base', $this->_rewriteBase);

            if (file_exists('.htaccess') && ($htaccess = file_get_contents('.htaccess'))) {
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

                    $ok = $ok && false !== file_put_contents('.htaccess', $htaccess);
                }
            }
        }
        
        $result = ($ok) ? $this->_rewriteBase : null;

        return $result;
    }

    public function makeBootstrap(): bool
    {

        $ok = true;

        $bootstrap = <<<BOOTSTRAP
<?php
\$is127 = (((\$host = array_shift(\$hostPort = explode(':', \$_SERVER['HTTP_HOST']))) . (isset(\$hostPort[1]) ? \$port = ':' . \$hostPort[1] : \$port = '') == '127.0.0.1' . \$port) ? \$hostname = 'localhost' : \$hostname = \$host) !== \$host;
\$isIndex = (((strpos(\$_SERVER['REQUEST_URI'], 'index.php')  > -1) ? \$requestUri = str_replace('index.php', '', \$_SERVER['REQUEST_URI']) : \$requestUri = \$_SERVER['REQUEST_URI']) !== \$_SERVER['REQUEST_URI']);

if(\$is127 || \$isIndex) {
    header('Location: //' . \$hostname . \$port . \$requestUri);
    exit(302);
}
include '../../framework/phink/phink_library.php';
include '../../framework/plugins/plugins_library.php';
        
BOOTSTRAP;

        // include '../../vendor/autoload.php';

        $serverApi = strtolower(PhpInfo::getGeneralSection()->server_api);

        if(strpos($serverApi, 'embedded') > -1 || strpos($serverApi, 'built-in') > -1) {
            $ok = false !== file_put_contents('bootstrap.php', $bootstrap);
        }

        return $ok;
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
