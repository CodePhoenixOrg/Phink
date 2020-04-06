<?php
// include '../../framework/phink/core/runner.php';

use Phink\Core\Runner;


// Runner::route(__DIR__);

$directory = __DIR__;

// chdir($directory);
$filePath = realpath($_SERVER["DOCUMENT_ROOT"] . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));

if ($filePath && is_dir($filePath)) {
    // attempt to find an index file
    foreach (['index.php', 'index.html'] as $indexFile) {
        if ($filePath = realpath($filePath . DIRECTORY_SEPARATOR . $indexFile)) {
            break;
        }
    }
}
if ($filePath && is_file($filePath)) {
    // 1. check that file is not outside of this directory for security
    // 2. check for circular reference to router.php
    // 3. don't serve dotfiles
    if (
        strpos($filePath, $directory . DIRECTORY_SEPARATOR) === 0 &&
        $filePath != $directory . DIRECTORY_SEPARATOR . 'router.php' &&
        substr(basename($filePath), 0, 1) != '.'
    ) {
        if (strtolower(substr($filePath, -4)) == '.php') {
            // php file; serve through interpreter
            include $filePath;
        } else {
            // asset file; serve from filesystem
            return false;
        }
    } else {
        // disallowed file
        header("HTTP/1.1 404 Not Found");
        echo "404 Not Found";
    }
} else {
    // rewrite to our index file
    include $directory . DIRECTORY_SEPARATOR . 'index.php';
}