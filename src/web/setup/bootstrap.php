<?php
$document_root = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : '';

$script_path = pathinfo($_SERVER['SCRIPT_FILENAME'], PATHINFO_DIRNAME);
if (substr($document_root, -4) !== 'web/' && ($p = strpos($script_path, 'src/web')) > -1) {
    $document_root = substr($script_path, 0, $p + 7);
}
define('SETUP_DOCUMENT_ROOT', $document_root . DIRECTORY_SEPARATOR);
define('SETUP_SRC_ROOT', substr(SETUP_DOCUMENT_ROOT, 0, -4));
define('SETUP_CONFIG_DIR', SETUP_SRC_ROOT . 'config' . DIRECTORY_SEPARATOR);
define('CONFIG_DIR', SETUP_CONFIG_DIR);

define('SETUP_SITE_ROOT', substr(SETUP_SRC_ROOT, 0, -4));

$vendor_dir = 'vendor' . DIRECTORY_SEPARATOR . 'phink' . DIRECTORY_SEPARATOR . 'phink' . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR;
$portable_dir = 'framework' . DIRECTORY_SEPARATOR;
$lib = 'phink' . DIRECTORY_SEPARATOR . 'phink_library.php';

$framework_dir = $vendor_dir;
if (file_exists(SETUP_SITE_ROOT . $portable_dir . $lib)) {
    $framework_dir = $portable_dir;
}

define('SETUP_FRAMEWORK', $framework_dir);

chdir('..');
include SETUP_SITE_ROOT . SETUP_FRAMEWORK . 'apps/setup/homepage.php';
