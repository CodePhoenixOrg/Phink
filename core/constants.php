<?php
/*
 * Copyright (C) 2016 David Blanchard
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
 
 
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Phink\Core;

define('WEB_SEPARATOR', '/');
define('TMP_DIR', 'tmp');

$PWD = '';
if(isset($_SERVER['DOCUMENT_ROOT'])) {
    define('BR', "<br />");
    if(PHP_OS == 'WINNT') {
        define('CR_LF', "\r\n");
        define('DOCUMENT_ROOT', str_replace('\\\\', '\\', $_SERVER['DOCUMENT_ROOT']) . '\\');
    } elseif(PHP_OS == 'Linux') {
        define('CR_LF', "\n");
        define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/');    
    } else {
        define('CR_LF', "\n");
        define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/');    
    }
    if(strstr($_SERVER['SERVER_SOFTWARE'], 'IIS')) {
        define('HTTP_PROTOCOL', ($_SERVER['HTTPS'] == 'off') ? 'http' : 'https');
    } elseif(strstr($_SERVER['SERVER_SOFTWARE'], 'Apache')) {
        define('HTTP_PROTOCOL', $_SERVER['REQUEST_SCHEME']);
    } elseif(strstr($_SERVER['SERVER_SOFTWARE'], 'lighttpd')) {
        define('HTTP_PROTOCOL', strstr($_SERVER['SERVER_PROTOCOL'], 'HTPPS') ? 'https' : 'http' );
    } elseif(strstr($_SERVER['SERVER_SOFTWARE'], 'nginx')) {
        define('HTTP_PROTOCOL', strstr($_SERVER['SERVER_PROTOCOL'], 'HTPPS') ? 'https' : 'http' );
    }
    
    $web = substr(DOCUMENT_ROOT, -4);
    
    if($web === 'web/') {
        define('APP_ROOT', substr(DOCUMENT_ROOT, 0, -4));
        define('RUNTIME_DIR', '..' . DIRECTORY_SEPARATOR . 'runtime' . DIRECTORY_SEPARATOR);
        define('RUNTIME_JS_DIR', 'js' . DIRECTORY_SEPARATOR . 'runtime' . DIRECTORY_SEPARATOR);
        define('CACHE_DIR', '..' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR);
    } else {
        define('APP_ROOT', DOCUMENT_ROOT);
        define('RUNTIME_DIR', 'runtime' . DIRECTORY_SEPARATOR);
        define('RUNTIME_JS_DIR', 'runtime' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR);
        define('CACHE_DIR', 'cache' . DIRECTORY_SEPARATOR);
    }
    
    define('LOG_PATH', APP_ROOT . 'logs/');
} else {
    define('APP_ROOT', './');
    define('DOCUMENT_ROOT', '');
    define('LOG_PATH', './');
}

$appconf = DOCUMENT_ROOT . 'config' . DIRECTORY_SEPARATOR . 'app.ini';

$appname = '';

if(file_exists($appconf)) {
    $appini = parse_ini_file($appconf);
    $appname = isset($appini['application']['name']) ? $appini['application']['name'] : '';
} 

if(empty($appname)) {
    $apppath = explode(DIRECTORY_SEPARATOR, DOCUMENT_ROOT);
    $appname = array_pop($apppath);
    $appname = strtolower(array_pop($apppath));
}

define('APP_NAME', $appname);

define('PAGE_NUMBER', 'pagen');
define('PAGE_COUNT', 'pagec');
define('PAGE_NUMBER_DEFAULT', 1);
define('PAGE_COUNT_DEFAULT', 20);
define('PAGE_COUNT_ZERO', 0);
define('MAIN_VIEW', 'main');
define('LOGIN_VIEW', 'login');
define('MASTER_VIEW', 'master');
define('HOME_VIEW', 'home');
define('MAIN_PAGE', '/' . MAIN_VIEW . '.html');
define('LOGIN_PAGE', '/' . LOGIN_VIEW . '.html');
define('MASTER_PAGE', '/' . MASTER_VIEW . '.html');
define('HOME_PAGE', '/' . HOME_VIEW . '.html');
define('LOG_FILE', LOG_PATH . 'debug.log');
define('APP_DATA', APP_ROOT . 'data' . DIRECTORY_SEPARATOR);
define('APP_BUSINESS', DOCUMENT_ROOT . 'app' . DIRECTORY_SEPARATOR . 'business' . DIRECTORY_SEPARATOR);
define('STARTER_FILE', 'starter.php');
define('HTTP_USER_AGENT', $_SERVER['HTTP_USER_AGENT']);
define('HTTP_HOST', $_SERVER['HTTP_HOST']);
define('HTTP_ORIGIN', (isset($_SERVER['HTTP_ORIGIN'])) ? $_SERVER['HTTP_ORIGIN'] : '');
define('HTTP_ACCEPT', $_SERVER['HTTP_ACCEPT']);
define('HTTP_PORT', $_SERVER['SERVER_PORT']);
define('REQUEST_URI', $_SERVER['REQUEST_URI']);
define('REQUEST_METHOD', $_SERVER['REQUEST_METHOD']);
define('QUERY_STRING', $_SERVER['QUERY_STRING']);
define('SERVER_NAME', $_SERVER['SERVER_NAME']);
define('SERVER_HOST', HTTP_PROTOCOL . '://' . HTTP_HOST);
define('SERVER_ROOT', HTTP_PROTOCOL . '://' . SERVER_NAME . ((HTTP_PORT !== '80') ? ':' . HTTP_PORT : ''));
define('BASE_URI', SERVER_NAME . ((HTTP_PORT !== '80') ? ':' . HTTP_PORT : '') . ((REQUEST_URI !== '') ? REQUEST_URI : ''));
define('FULL_URI', HTTP_PROTOCOL . '://' . BASE_URI);
define('FULL_SSL_URI', 'https://' . BASE_URI);
define('ROOT_NAMESPACE', 'Phink');
define('ROOT_PATH', 'phink');
define('DEFALT_MODEL', ROOT_NAMESPACE . '\\MVC\\TModel');
define('DEFAULT_CONTROLLER', ROOT_NAMESPACE . '\\MVC\\TController');
define('DEFAULT_PARTIAL_CONTROLLER', ROOT_NAMESPACE . '\\MVC\\TPartialController');
define('DEFAULT_CONTROL', ROOT_NAMESPACE . '\\Web\\UI\\TControl');
define('CONTROLLER', 'TController');
define('PARTIAL_CONTROLLER', 'TPartialController');
define('CONTROL', 'TControl');
define('CLASS_EXTENSION', '.class.php');
define('HTML_EXTENSION', '.html');
define('PREHTML_EXTENSION', '.phtml');
define('PATTERN_EXTENSION', '.pattern' . PREHTML_EXTENSION);
define('JS_EXTENSION', '.js');
define('JSON_EXTENSION', '.json');
define('CSS_EXTENSION', '.css');
define('PHX_TERMINATOR', '<phx:eof />');
define('CREATIONS_PLACEHOLDER', '<phx:creationsPlaceHolder />');
define('ADDITIONS_PLACEHOLDER', '<phx:additionsPlaceHolder />');
define('AFTERBINDING_PLACEHOLDER', '<phx:afterBindingPlaceHolder />');
define('HTML_PLACEHOLDER', '<phx:htmlPlaceHolder />');
/*
 * define('CONTROL_ADDITIONS', CR_LF . "\tpublic function createObjects() {" . CR_LF . CREATIONS_PLACEHOLDER . CR_LF . "\t}" . CR_LF . CR_LF . "\tpublic function declareObjects() {" . CR_LF . ADDITIONS_PLACEHOLDER . CR_LF . "\t}" . CR_LF . CR_LF . "\tpublic function afterBindingObjects() {" . CR_LF . AFTERBINDING_PLACEHOLDER . CR_LF . "\t}" . CR_LF . CR_LF . "\tpublic function displayHtml() {" . CR_LF . "?>" . CR_LF . HTML_PLACEHOLDER . CR_LF . "<?php" . CR_LF . "\t}" . CR_LF . '}' . CR_LF);
 */
define('CONTROL_ADDITIONS', CR_LF . "\tpublic function createObjects() {" . CR_LF . CREATIONS_PLACEHOLDER . CR_LF . "\t}" . CR_LF . CR_LF . "\tpublic function declareObjects() {" . CR_LF . ADDITIONS_PLACEHOLDER . CR_LF . "\t}" . CR_LF . CR_LF . "\tpublic function displayHtml() {" . CR_LF . "?>" . CR_LF . HTML_PLACEHOLDER . CR_LF . "<?php" . CR_LF . "\t}" . CR_LF . '}' . CR_LF);
define('PHX_SQL_LIMIT', '<phx:sql_limit />');

define('RETURN_CODE', 1);
define('INCLUDE_FILE', 2);
