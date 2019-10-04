<?php 

namespace Phink\Core;

use Phink\Registry\TRegistry;

trait TIniLoader
{
    public function loadINI(string $path = ''): void
    {
        $ini = null;
        if (!file_exists($path . 'config/app.ini')) {
            return;
        }

        $ini = parse_ini_file($path  . 'config/app.ini', TRUE, INI_SCANNER_TYPED);
        if(isset($ini['application']['name'])) {
            TRegistry::write('Application', 'name', $ini['application']['name']);
        }
        if(isset($ini['application']['title'])) {
            TRegistry::write('Application', 'title', $ini['application']['title']);
        }
        
        foreach($ini as $key=>$values) {
            TRegistry::write('ini', $key, $values);
        }
        unset($ini);

        $dataPath = realpath($path . 'data');
        if(file_exists($dataPath)) {
            $dataDir = dir($dataPath);

            $entry = '';
            while (($entry = $dataDir->read()) !== false) {
                $info = (object) \pathinfo($entry);

                if ($info->extension == 'json') {
                    $conf = file_get_contents($dataPath . DIRECTORY_SEPARATOR . $entry);
                    $conf = json_decode($conf, true);
                    TRegistry::write('connections', $info->filename, $conf);
                    // self::getLogger()->dump('DATA CONF ' . $info->filename, $conf);
                }
            }
            $dataDir->close();
        }
    }

}