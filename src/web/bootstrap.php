<?php
define('CONFIG_DIR', '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR);
define('FRAMEWORK', trim(file_get_contents(CONFIG_DIR . 'framework')));
// include FRAMEWORK . 'phink' . DIRECTORY_SEPARATOR . 'phink_library.php';
// include FRAMEWORK . 'plugins' . DIRECTORY_SEPARATOR . 'plugins_library.php';
include '../../vendor/autoload.php';