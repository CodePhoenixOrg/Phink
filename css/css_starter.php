<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$filenames = [
    'bootstrap.css'
    ,'bootstrap-theme.css'
    ,'jquery-ui.css'
    ,'jquery-ui.structure.css'
    ,'jquery-ui.theme.css'    
    //,'jquerysctipttop.css'
    //,'multiaccordion.jquery.css'
    //,'prettify.css'
//    ,'drag-and-drop.css'
];

$srcdir =  dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..'  . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'javascript' . DIRECTORY_SEPARATOR . 'thirdparty' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR;

$destdir = DOCUMENT_ROOT . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR;

$css_filename = $destdir . '_3rdparty.css';

$css_content = '';
foreach ($filenames as $filename) {
    $css_content .= file_get_contents($srcdir . $filename, FILE_USE_INCLUDE_PATH);

}

file_put_contents($css_filename, $css_content);
