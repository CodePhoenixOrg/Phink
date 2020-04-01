<?php
/*
 * Copyright (C) 2019 David Blanchard
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

namespace Phink\Core;

define('WEB_SEPARATOR', '/');
define('TMP_DIR', 'tmp');

$PWD = '';

$document_root = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : '';

define('APP_IS_WEB', ($document_root !== ''));
define('APP_IS_PHAR', (\Phar::running() !== ''));
define('ROOT_NAMESPACE', 'Phink');
define('ROOT_PATH', 'phink');
// define('DOCUMENT_SCRIPT', $_SERVER['SCRIPT_FILENAME']);
if (APP_IS_WEB) {
    define('BR', "<br />");

    if (PHP_OS == 'WINNT') {
        $document_root = str_replace('\\\\', '\\', $document_root);
    }

    if (substr($document_root, -4) !== 'web/') {
        $document_root = pathinfo($_SERVER['SCRIPT_FILENAME'], PATHINFO_DIRNAME);
    }

    define('DOCUMENT_ROOT', $document_root . DIRECTORY_SEPARATOR);
    define('SRC_ROOT', substr(DOCUMENT_ROOT, 0, -4));

    $rewrite_base = '/';
    if ($htaccess = file_get_contents(DOCUMENT_ROOT . '.htaccess')) {

        $htaccess = strtolower($htaccess);
        $htaccess = str_replace(PHP_EOL, ';', $htaccess);
        $ps = strpos($htaccess, 'rewritebase');
        if ($ps > -1) {
            $ps += 11;
            $pe = strpos($htaccess, ';', $ps);
            $rewrite_base = substr($htaccess, $ps, $pe - $ps);
            $rewrite_base = trim($rewrite_base);
        }
    }
    define('REWRITE_BASE', $rewrite_base);

    $scheme = 'http';
    if (strstr($_SERVER['SERVER_SOFTWARE'], 'IIS')) {
        $scheme = ($_SERVER['HTTPS'] == 'off') ? 'http' : 'https';
    } elseif (strstr($_SERVER['SERVER_SOFTWARE'], 'Apache')) {
        $scheme = $_SERVER['REQUEST_SCHEME'];
    } elseif (strstr($_SERVER['SERVER_SOFTWARE'], 'lighttpd')) {
        $scheme = strstr($_SERVER['SERVER_PROTOCOL'], 'HTPPS') ? 'https' : 'http';
    } elseif (strstr($_SERVER['SERVER_SOFTWARE'], 'nginx')) {
        $scheme = strstr($_SERVER['SERVER_PROTOCOL'], 'HTPPS') ? 'https' : 'http';
    }

    define('HTTP_PROTOCOL', $scheme);
    define('SITE_ROOT', substr(SRC_ROOT, 0, -4));

    $appname = pathinfo(SITE_ROOT, PATHINFO_FILENAME);
    define('APP_NAME', $appname);

    // define('PHINK_VENDOR_SRC', 'vendor' . DIRECTORY_SEPARATOR . 'phink' . DIRECTORY_SEPARATOR . 'phink' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR);
    define('PHINK_VENDOR_SRC', 'framework' . DIRECTORY_SEPARATOR);
    define('PHINK_VENDOR_LIB', PHINK_VENDOR_SRC . 'phink' . DIRECTORY_SEPARATOR);
    define('PHINK_VENDOR_WIDGETS', PHINK_VENDOR_SRC . 'widgets' . DIRECTORY_SEPARATOR);
    define('PHINK_VENDOR_PLUGINS', PHINK_VENDOR_SRC . 'plugins' . DIRECTORY_SEPARATOR);
    define('PHINK_VENDOR_APPS', PHINK_VENDOR_SRC . 'apps' . DIRECTORY_SEPARATOR);
    define('PHINKJS_VENDOR', 'vendor' . DIRECTORY_SEPARATOR . 'phink' . DIRECTORY_SEPARATOR . 'phinkjs' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'client' . DIRECTORY_SEPARATOR);
    define('PHINK_ROOT', SITE_ROOT . PHINK_VENDOR_LIB);
    define('PHINK_WIDGETS_ROOT', SITE_ROOT . PHINK_VENDOR_WIDGETS);
    define('PHINK_PLUGINS_ROOT', SITE_ROOT . PHINK_VENDOR_PLUGINS);
    define('PHINK_APPS_ROOT', SITE_ROOT . PHINK_VENDOR_APPS);
    define('PHINKJS_ROOT', SITE_ROOT . PHINKJS_VENDOR);

    // Puzzle Library constants 
    define('PUZZLE_VENDOR_SRC', PHINK_VENDOR_SRC);
    define('PUZZLE_VENDOR_LIB', PUZZLE_VENDOR_SRC . 'puzzle' . DIRECTORY_SEPARATOR);
    define('PUZZLE_VENDOR_APPS', PHINK_VENDOR_APPS);
    define('PUZZLEJS_VENDOR', PUZZLE_VENDOR_LIB . 'js' . DIRECTORY_SEPARATOR);
    define('PUZZLE_ROOT', SITE_ROOT . PUZZLE_VENDOR_LIB);
    define('PUZZLE_APPS_ROOT', PHINK_APPS_ROOT);
    define('PUZZLEJS_ROOT', SITE_ROOT . PUZZLEJS_VENDOR);

    define('APP_DIR', 'app' . DIRECTORY_SEPARATOR);

    define('APP_ROOT', SRC_ROOT . APP_DIR);
    define('CONTROLLER_ROOT', APP_ROOT . 'controllers' . DIRECTORY_SEPARATOR);
    define('MODEL_ROOT', APP_ROOT . 'models' . DIRECTORY_SEPARATOR);
    define('REST_ROOT', APP_ROOT . 'rest' . DIRECTORY_SEPARATOR);
    define('VIEW_ROOT', APP_ROOT . 'views' . DIRECTORY_SEPARATOR);
    define('BUSINESS_DIR', APP_DIR . 'business' . DIRECTORY_SEPARATOR);
    define('BUSINESS_ROOT', SRC_ROOT . BUSINESS_DIR);

    define('REL_RUNTIME_DIR', 'runtime' . DIRECTORY_SEPARATOR);
    define('RUNTIME_DIR', SRC_ROOT . REL_RUNTIME_DIR);
    define('REL_RUNTIME_JS_DIR', 'js' . DIRECTORY_SEPARATOR . 'runtime' . DIRECTORY_SEPARATOR);
    define('REL_RUNTIME_CSS_DIR', 'css' . DIRECTORY_SEPARATOR . 'runtime' . DIRECTORY_SEPARATOR);
    define('RUNTIME_JS_DIR', DOCUMENT_ROOT . REL_RUNTIME_JS_DIR);
    define('RUNTIME_CSS_DIR', DOCUMENT_ROOT . REL_RUNTIME_CSS_DIR);
    define('CACHE_DIR', SRC_ROOT . 'cache' . DIRECTORY_SEPARATOR);

    define('LOG_PATH', SRC_ROOT . 'logs' . DIRECTORY_SEPARATOR);

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
    define('APP_DATA', SRC_ROOT . 'data' . DIRECTORY_SEPARATOR);
    define('APP_BUSINESS', APP_ROOT . 'business' . DIRECTORY_SEPARATOR);
    define('STARTER_FILE', 'starter.php');
    define('HTTP_USER_AGENT', $_SERVER['HTTP_USER_AGENT']);
    define('HTTP_HOST', $_SERVER['HTTP_HOST']);
    define('HTTP_ORIGIN', (isset($_SERVER['HTTP_ORIGIN'])) ? $_SERVER['HTTP_ORIGIN'] : '');
    define('HTTP_ACCEPT', (isset($_SERVER['HTTP_ACCEPT'])) ? $_SERVER['HTTP_ACCEPT'] : '');
    define('HTTP_PORT', $_SERVER['SERVER_PORT']);
    define('COOKIE', $_COOKIE);
    define('REQUEST_URI', $_SERVER['REQUEST_URI']);
    define('REQUEST_METHOD', $_SERVER['REQUEST_METHOD']);
    define('QUERY_STRING', $_SERVER['QUERY_STRING']);
    define('SERVER_NAME', $_SERVER['SERVER_NAME']);
    define('SERVER_HOST', HTTP_PROTOCOL . '://' . HTTP_HOST);
    define('SERVER_ROOT', HTTP_PROTOCOL . '://' . SERVER_NAME . ((HTTP_PORT !== '80' && HTTP_PORT !== '443') ? ':' . HTTP_PORT : ''));
    define('BASE_URI', SERVER_NAME . ((HTTP_PORT !== '80') ? ':' . HTTP_PORT : '') . ((REQUEST_URI !== '') ? REQUEST_URI : ''));
    define('FULL_URI', HTTP_PROTOCOL . '://' . BASE_URI);
    define('FULL_SSL_URI', 'https://' . BASE_URI);
    define('DEFAULT_MODEL', ROOT_NAMESPACE . '\\MVC\\TModel');
    define('DEFAULT_CONTROLLER', ROOT_NAMESPACE . '\\MVC\\TController');
    define('DEFAULT_PARTIAL_CONTROLLER', ROOT_NAMESPACE . '\\MVC\\TPartialController');
    define('DEFAULT_CONTROL', ROOT_NAMESPACE . '\\Web\\UI\\TControl');
    define('DEFAULT_PARTIAL_CONTROL', ROOT_NAMESPACE . '\\Web\\UI\\TPartialControl');
} else {
    define('DOCUMENT_ROOT', '');
    define('LOG_PATH', './logs/');
}

define('CONTROLLER', 'TController');
define('PARTIAL_CONTROLLER', 'TPartialController');
define('CONTROL', 'TControl');
define('PARTIAL_CONTROL', 'TPartialControl');
define('CLASS_EXTENSION', '.class.php');
define('HTML_EXTENSION', '.html');
define('PREHTML_EXTENSION', '.phtml');
define('JS_EXTENSION', '.js');
define('JSON_EXTENSION', '.json');
define('CSS_EXTENSION', '.css');
define('PHX_TERMINATOR', '<phx:eof />');
define('CREATIONS_PLACEHOLDER', '<phx:creationsPlaceHolder />');
define('ADDITIONS_PLACEHOLDER', '<phx:additionsPlaceHolder />');
define('AFTERBINDING_PLACEHOLDER', '<phx:afterBindingPlaceHolder />');
define('HTML_PLACEHOLDER', '<phx:htmlPlaceHolder />');
define('JS_PLACEHOLDER', '<phx:jsPlaceHolder />');
define('CSS_PLACEHOLDER', '<phx:cssPlaceHolder />');
define('ASP_OPEN_VAR', '<%');
define('ASP_CLOSE_VAR', '%>');
define('OPEN_VAR', '{{');
define('CLOSE_VAR', '}}');

/*
* define('CONTROL_ADDITIONS', PHP_EOL . "\tpublic function createObjects() {" . PHP_EOL . CREATIONS_PLACEHOLDER . PHP_EOL . "\t}" . PHP_EOL . PHP_EOL . "\tpublic function declareObjects() {" . PHP_EOL . ADDITIONS_PLACEHOLDER . PHP_EOL . "\t}" . PHP_EOL . PHP_EOL . "\tpublic function afterBindingObjects() {" . PHP_EOL . AFTERBINDING_PLACEHOLDER . PHP_EOL . "\t}" . PHP_EOL . PHP_EOL . "\tpublic function displayHtml() {" . PHP_EOL . "?>" . PHP_EOL . HTML_PLACEHOLDER . PHP_EOL . "<?php" . PHP_EOL . "\t}" . PHP_EOL . '}' . PHP_EOL);
*/
define('CONTROL_ADDITIONS', PHP_EOL . "\tpublic function createObjects() : void {" . PHP_EOL . CREATIONS_PLACEHOLDER . PHP_EOL . "\t}" . PHP_EOL . PHP_EOL . "\tpublic function declareObjects() : void {" . PHP_EOL . ADDITIONS_PLACEHOLDER . PHP_EOL . "\t}" . PHP_EOL . PHP_EOL . "\tpublic function displayHtml() : void {" . PHP_EOL . "?>" . PHP_EOL . HTML_PLACEHOLDER . PHP_EOL . "<?php" . PHP_EOL . "\t}" . PHP_EOL . '}' . PHP_EOL);
define('PHX_SQL_LIMIT', '<phx:sql_limit />');

define('RETURN_CODE', 1);
define('INCLUDE_FILE', 2);
define('REQUEST_TYPE_WEB', 'web');
define('REQUEST_TYPE_REST', 'rest');

define('DEBUG_LOG', LOG_PATH . 'debug.log');
define('ERROR_LOG', LOG_PATH . 'error.log');
define('SQL_LOG', LOG_PATH . 'sql.log');

$appconf = DOCUMENT_ROOT . 'config' . DIRECTORY_SEPARATOR . 'app.ini';

$appname = '';

// if (file_exists($appconf)) {
//     $appini = parse_ini_file($appconf);
//     $appname = isset($appini['application']['name']) ?? $appini['application']['name'];
// }

// if (empty($appname)) {
//     $apppath = explode(DIRECTORY_SEPARATOR, DOCUMENT_ROOT);
//     $appname = array_pop($apppath);
//     $appname = strtolower(array_pop($apppath));
// }
