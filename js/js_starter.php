<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;

$js_filename = $dir . 'code_phoenix.js';

$filenames = [
        'global.js'
    ,   'core/registry.js'
    ,   'web/web_application.js'
    ,   'web/web_object.js'
    ,   'mvc/controller.js'
];

$js_content = '';

foreach ($filenames as $filename) {
    $js_content .= file_get_contents($dir . $filename);
}
 
file_put_contents($js_filename, $js_content);
