<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
$destdir = DOCUMENT_ROOT . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR;

$js_filename = $destdir . 'code_phoenix.js';

$filenames = [
        'global.js'
    ,   'core/registry.js'
    ,   'core/utils.js'
    ,   'web/web_application.js'
    ,   'web/web_object.js'
    ,   'web/ui/plugin.js'
    ,   'web/ui/plugin/accordion.js'
    ,   'web/ui/plugin/table.js'
    ,   'mvc/controller.js'
    ,   'jphoenix.js'
];

$js_content = '';

foreach ($filenames as $filename) {
    $js_content .= file_get_contents($dir . $filename);
}
 
file_put_contents($js_filename, $js_content);

$srcdir =  dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..'  . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'javascript' . DIRECTORY_SEPARATOR . 'thirdparty' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR;

$filenames = [
        'widgets.js'
    ,   'jquery.js'
    ,   'jquery-ui.js'
    //,   'multiaccordion.jquery.js'
    ,   'bootstrap.js'
    //,   'holder.js'
    //,   'prettify.js'
    ,   'php.default.min.js'
    ,   'drag-and-drop.js'
];

foreach ($filenames as $filename) {
    $js_content = file_get_contents($srcdir . $filename, FILE_USE_INCLUDE_PATH);
    file_put_contents($destdir . $filename, $js_content);

}

