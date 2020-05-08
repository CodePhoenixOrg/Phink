<?php
$is127 = ((($host = array_shift($hostPort = explode(':', $_SERVER['HTTP_HOST']))) . (isset($hostPort[1]) ? $port = ':' . $hostPort[1] : $port = '') == '127.0.0.1' . $port) ? $hostname = 'localhost' : $hostname = $host) !== $host;
$isIndex = (((strpos($_SERVER['REQUEST_URI'], 'index.php')  > -1) ? $requestUri = str_replace('index.php', '', $_SERVER['REQUEST_URI']) : $requestUri = $_SERVER['REQUEST_URI']) !== $_SERVER['REQUEST_URI']);

if($is127 || $isIndex) {
    header('Location: //' . $hostname . $port . $requestUri);
    exit(302);
}
define('CONFIG_DIR', '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR);
define('FRAMEWORK', trim(file_get_contents(CONFIG_DIR . 'framework')));
// include FRAMEWORK . 'phink' . DIRECTORY_SEPARATOR . 'phink_library.php';
// include FRAMEWORK . 'plugins' . DIRECTORY_SEPARATOR . 'plugins_library.php';
include '../../vendor/autoload.php';