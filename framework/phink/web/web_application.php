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


namespace Phink\Web;

//require_once 'phink/core/application.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Phink\TAutoloader;
use Phink\Auth\TAuthentication;
use Phink\Core\TRouter;
use Phink\Registry\TRegistry;
use Phink\Rest\TRestRouter;
use Phink\Web\TWebRouter;
use Phink\Cache\TCache;
use \Phink\Core\TCustomApplication;

/**
 * Description of router
 *
 * @author David
 */
class TWebApplication extends TCustomApplication implements IHttpTransport, IWebObject
{
    use \Phink\Web\TWebObject;

    protected $params = '';
    protected static $instance = null;

    public function __construct()
    {
        parent::__construct();
        parent::ignite();

        TCache::createRuntimeDirs();

        $this->authentication = new TAuthentication();
        $this->request = new TRequest();
        $this->response = new TResponse();

        if (class_exists('Twig\Environment')) {
            $loader = new \Twig\Loader\FilesystemLoader(VIEW_ROOT);
            $this->twigEnvironment = new \Twig\Environment($loader, [
                'cache' => CACHE_DIR,
            ]);
        }
    }

    public function getCookie(string $name): ?array
    {
        return isset(COOKIE[$name]) ? COOKIE[$name] : null;
    }

    public function execute(): void
    {
        // foreach ($this->commands as $long => $param) {
        //     $short = $param['short'];
        //     $callback = $param['callback'];
        //     if (isset($_REQUEST[$short])) {
        //         $result = $_REQUEST[$short];
        //         $isFound = true;
        //     } elseif (isset($_REQUEST[$long])) {
        //         $result = $_REQUEST[$long];
        //         $isFound = true;
        //     }
        //     if ($isFound) {
        //         break;
        //     }
        // }

        // if ($callback !== null && $isFound && $result === null) {
        //     call_user_func($callback);
        // } elseif ($callback !== null && $isFound && $result !== null) {
        //     call_user_func($callback, $result);
        // }
    }

    public static function mediaPath(): string
    {
        return SERVER_ROOT . REWRITE_BASE . 'media/';
    }

    public static function themePath(): string
    {
        return SERVER_ROOT . REWRITE_BASE . 'themes/';
    }

    public static function imagePath(): string
    {
        return self::mediaPath() . 'images/';
    }

    public static function create(...$params): void
    {
        session_start();
        self::$instance = new TWebApplication();
        self::$instance->run($params);
    }

    public static function getInstance(): TWebApplication
    {
        return self::$instance;
    }

    protected function displayConstants(): array
    {
        $constants = [];
        $constants['REWRITE_BASE'] = REWRITE_BASE;
        $constants['DOCUMENT_ROOT'] = DOCUMENT_ROOT;
        $constants['HTTP_PROTOCOL'] = HTTP_PROTOCOL;
        $constants['SRC_ROOT'] = SRC_ROOT;
        $constants['PHINK_ROOT'] = PHINK_ROOT;
        $constants['APP_NAME'] = APP_NAME;
        $constants['APP_ROOT'] = APP_ROOT;
        $constants['CONTROLLER_ROOT'] = CONTROLLER_ROOT;
        $constants['MODEL_ROOT'] = MODEL_ROOT;
        $constants['REST_ROOT'] = REST_ROOT;
        $constants['VIEW_ROOT'] = VIEW_ROOT;
        $constants['BUSINESS_ROOT'] = BUSINESS_ROOT;
        $constants['REL_RUNTIME_DIR'] = REL_RUNTIME_DIR;
        $constants['RUNTIME_DIR'] = RUNTIME_DIR;
        $constants['REL_RUNTIME_JS_DIR'] = REL_RUNTIME_JS_DIR;
        $constants['RUNTIME_JS_DIR'] = RUNTIME_JS_DIR;
        $constants['CACHE_DIR'] = CACHE_DIR;
        $constants['LOG_PATH'] = LOG_PATH;
        $constants['DEBUG_LOG'] = DEBUG_LOG;
        $constants['ERROR_LOG'] = ERROR_LOG;
        $constants['APP_DATA'] = APP_DATA;
        $constants['APP_BUSINESS'] = APP_BUSINESS;
        $constants['STARTER_FILE'] = STARTER_FILE;
        $constants['HTTP_USER_AGENT'] = HTTP_USER_AGENT;
        $constants['HTTP_HOST'] = HTTP_HOST;
        $constants['HTTP_ORIGIN'] = HTTP_ORIGIN;
        $constants['HTTP_ACCEPT'] = HTTP_ACCEPT;
        $constants['HTTP_PORT'] = HTTP_PORT;
        $constants['REQUEST_URI'] = REQUEST_URI;
        $constants['REQUEST_METHOD'] = REQUEST_METHOD;
        $constants['QUERY_STRING'] = QUERY_STRING;
        $constants['SERVER_NAME'] = SERVER_NAME;
        $constants['SERVER_HOST'] = SERVER_HOST;
        $constants['SERVER_ROOT'] = SERVER_ROOT;
        $constants['BASE_URI'] = BASE_URI;
        $constants['FULL_URI'] = FULL_URI;
        $constants['FULL_SSL_URI'] = FULL_SSL_URI;
        $constants['ROOT_NAMESPACE'] = ROOT_NAMESPACE;
        $constants['ROOT_PATH'] = ROOT_PATH;

        TRegistry::write('console', 'buffer', $constants);

        \Phink\UI\TConsoleApplication::writeLine('Application constants are :');
        foreach ($constants as $key => $value) {
            \Phink\UI\TConsoleApplication::writeLine($key . ' => ' . $value);
        }

        return $constants;
    }

    public function run(...$params): bool
    {
        $result = true;

        if (!file_exists(RUNTIME_DIR . 'phink_builder.lock')) {
            \Phink\JavaScript\PhinkBuilder::build();
            file_put_contents(RUNTIME_DIR . 'phink_builder.lock', date('Y-m-d h:i:s'));
        }

        if (!file_exists(RUNTIME_DIR . 'js_builder.lock')) {
            \Phink\JavaScript\JsBuilder::build();
            file_put_contents(RUNTIME_DIR . 'js_builder.lock', date('Y-m-d h:i:s'));
        }

        if (!file_exists(RUNTIME_DIR . 'css_builder.lock')) {
            \Phink\CascadingStyleSheet\CssBuilder::build();
            file_put_contents(RUNTIME_DIR . 'css_builder.lock', date('Y-m-d h:i:s'));
        }

        $this->params = $params;

        $router = new \Phink\Core\TRouter($this);
        $reqtype = $router->match();

        if (!$router->isFound()) {
            $this->response->setReturn(404);
            return false;
        }

        if ($reqtype == REQUEST_TYPE_WEB) {
            if ($this->validateToken($router)) {
                $router = new TWebRouter($router);
            }
        } else {
            $router = new TRestRouter($router);
        }

        if ($router->translate()) {
            // self::getLogger()->debug("Ready to dispatch");
            $router->dispatch();
        } else {
            $this->response->setReturn(403);
        }

        return $result;
    }

    public function validateToken(TRouter $router): bool
    {
        $result = false;
        // We get the current token ...
        //        $token = $this->request->getToken();
        $token = TRequest::getQueryStrinng('token');
        //        if(!is_string($token) && !isset($_SESSION['USER'])) {
        //            $token = '#!';
        //            $token = TCrypto::generateToken('');
        //        }

        self::getLogger()->debug("TRANSLATED ROUTE::" . $router->getPath());
        self::getLogger()->debug("TRANSLATED SRC ROUTE::" . SRC_ROOT . $router->getPath());
        self::getLogger()->debug("TRANSLATED PHINK ROUTE::" . SITE_ROOT . $router->getPath());

        if ((is_string($token)
                || (file_exists(SRC_ROOT . $router->getPath()) || file_exists(SITE_ROOT . $router->getPath())))
            && $router->isFound()
        ) {
            // We renew the token
            // ... we'll try to match the user with this token and alternatively get a new token
            // so that it can no longer be used
            $token = $this->authentication->renewToken($token);
            //        $result = TAuthentication::getPermissionByToken($token);
            // we place the new token in the response
            $this->response->setToken($token);
            $result = true;
        } else {
            $this->response->setReturn(404);
            $result = false;
        }

        return $result;
    }

    public static function write($string, ...$params): void
    {
        $result = self::_write($string, $params);
        TRegistry::write('console', 'buffer', $result);
        self::getLogger()->debug($result);
    }

    public static function writeLine($string, ...$params): void
    {
        $result = self::_write($string, $params);
        TRegistry::write('console', 'buffer', $result);
        self::getLogger()->debug($result . PHP_EOL);
    }

    public static function writeException(\Throwable $ex, $file = null, $line = null): void
    {
        self::getLogger()->error($ex, $file, $line);
    }
}
