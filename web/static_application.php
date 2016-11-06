<?php

namespace Phink\Web;

require_once 'phink/web/web_application.php';

/**
 * Description of program
 *
 * @author david
 */
class TStaticApplication extends TWebApplication
{

    public static function implicitLink()
    {
        return SERVER_ROOT . REQUEST_URI;
    }

    public static function cache($filename, $content)
    {
        $filename = DOCUMENT_ROOT . DIRECTORY_SEPARATOR . CAChE_DIR . DIRECTORY_SEPARATOR . $filename;
        $filename = \Phink\Utils\TFileUtils::filePath($filename);
        file_put_contents($filename, $content);
    } 

    public function getStaticFileName()
    {
        $filename = DOCUMENT_ROOT . 'cache' . ((REQUEST_URI == '/') ? MAIN_PAGE : REQUEST_URI);
        \Phink\Log\TLog::debug(__METHOD__ . '::' . $filename);
        
        if(strstr(HTTP_ACCEPT, 'json')) {
            $filename = str_replace('.html', '.json', $filename);
        }
        $filename = \Phink\Utils\TFileUtils::filePath($filename);
        $p = strpos($filename, '?');
        $filename = str_replace('?', '_', str_replace('&', '_', str_replace('.', '_', str_replace('=', '_', $filename)))) . HTML_EXTENSION;
        //\Phink\Log\TLog::debug('STATIC FILENAME : ' . $filename, __FILE__, __LINE__);
        
        return $filename;
    }

    public static function create($params = array())
    {
        (new TStaticApplication())->run($params);
    }

    public function run($params)
    {
        if(strstr(HTTP_ACCEPT, 'partialview')) {
            parent::run($params);
        } else {
            if($this->validateToken()) {
                $filename = $this->getStaticFileName();
                //\Phink\Log\TLog::debug('HTTP_ACCEPT : ' . HTTP_ACCEPT, __FILE__, __LINE__);
                if (!file_exists($filename)) {
                    ob_start();
                    parent::create();
                    $contents = ob_get_clean();
                    if(!strstr($filename, '.json')) {
                        file_put_contents($filename, $contents);
                    }
                    echo $contents;
                } else {
                    include $filename;
                }
            }
        }
    }
}
