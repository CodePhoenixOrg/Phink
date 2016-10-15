<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$destdir = DOCUMENT_ROOT . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR;

$srcdir =  dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..'  . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'javascript' . DIRECTORY_SEPARATOR . 'thirdparty' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR;

$js_filename = $destdir . '_3rdparty.js';

$filenames = [
       'widgets.js'
    ,   'jquery.js'
    ,   'jquery-ui.js'
    ,   'jquery.ui.touch-punch.min.js'
    //,   'multiaccordion.jquery.js'
    ,   'bootstrap.js'
    //,   'holder.js'
    //,   'prettify.js'
    ,   'php.default.min.js'
//    ,   'tiny-console.js'
//    ,   'drag-and-drop.js'
];

$js_content = '';
foreach ($filenames as $filename) {
    $js_content .= file_get_contents($srcdir . $filename, FILE_USE_INCLUDE_PATH);
}
file_put_contents($js_filename, $js_content);

$dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;

$js_filename = $destdir . 'code_phoenix.js';

$filenames = [
        'global.js'
    ,   'core' . DIRECTORY_SEPARATOR . 'registry.js'
    ,   'core' . DIRECTORY_SEPARATOR . 'utils.js'
    ,   'core' . DIRECTORY_SEPARATOR . 'object.js'
    ,   'core' . DIRECTORY_SEPARATOR . 'url.js'
    ,   'web' . DIRECTORY_SEPARATOR . 'rest.js'
    ,   'web' . DIRECTORY_SEPARATOR . 'web_object.js'
    ,   'web' . DIRECTORY_SEPARATOR . 'web_application.js'
    ,   'mvc' . DIRECTORY_SEPARATOR . 'view.js'
    ,   'mvc' . DIRECTORY_SEPARATOR . 'controller.js'
    ,   'web' . DIRECTORY_SEPARATOR . 'ui' . DIRECTORY_SEPARATOR . 'plugin.js'
    ,   'web' . DIRECTORY_SEPARATOR . 'ui' . DIRECTORY_SEPARATOR . 'plugin' . DIRECTORY_SEPARATOR . 'accordion.js'
    ,   'web' . DIRECTORY_SEPARATOR . 'ui' . DIRECTORY_SEPARATOR . 'plugin' . DIRECTORY_SEPARATOR . 'list.js'
    ,   'web' . DIRECTORY_SEPARATOR . 'ui' . DIRECTORY_SEPARATOR . 'plugin' . DIRECTORY_SEPARATOR . 'table.js'
];

$js_content = '';

foreach ($filenames as $filename) {
    $js_content .= file_get_contents($dir . $filename);
}

file_put_contents($js_filename, $js_content);

$filenames = [
        'core' . DIRECTORY_SEPARATOR . 'debug.js'
    ,   'core' . DIRECTORY_SEPARATOR . 'console.js'
    ,   'jphink.js'
];

$js_content = '';

foreach ($filenames as $filename) {
    $js_content = file_get_contents($dir . $filename);
    $info = explode(DIRECTORY_SEPARATOR, $filename);
    $filename = array_pop($info);
    file_put_contents($destdir . $filename, $js_content);
}