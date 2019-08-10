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

namespace Phink\Web;

/**
 * Description of TObject
 *
 * @author david
 */

use Phink\Registry\TRegistry;
use Phink\Core\TCustomApplication;
use Phink\Web\UI\TCustomControl;
use Phink\TAutoloader;

trait TWebObject
{
    use THttpTransport;

    private static $_currentDirectory;
    private static $_currentFilePath;
    private static $_currentClassName;
    private static $_currentNamespace;
    private static $_sqlConfigurationFileName;
    private static $_pageNumber;
    private static $_pageCount;
    protected $redis = null;
    //    protected $response = null;
    //    protected $request = null;
    protected $modelFileName = '';
    protected $viewFileName = '';
    protected $controllerFileName = '';
    protected $jsControllerFileName = '';
    protected $cssFileName = '';
    protected $cacheFileName = '';
    protected $preHtmlName = '';
    protected $viewName = '';
    protected $actionName = '';
    protected $className = '';
    protected $dirName = '';
    protected $namespace = '';
    protected $code = '';
    protected $parameters = [];
    protected $commands = [];
    protected $application = null;
    protected $viewIsInternal = false;
    protected $path = '';
    protected $twigEnvironment = null;
    protected $parentView = null;
    protected $parentType = null;
    protected $motherView = null;
    protected $motherUID = '';

    //    protected $authentication = null;

    //    public function __construct(IObject $parent)
    //    {
    //        $this->request = $parent->getRequest();
    //        $this->response = $parent->getResponse();
    //    }

    public static function pageNumber($value = null)
    {
        if (isset($value)) {
            self::$_pageNumber = $value;
        } else {
            return self::$_pageNumber;
        }
    }

    public static function pageCount($value = null)
    {
        if (isset($value)) {
            self::$_pageCount = $value;
        } else {
            return self::$_pageCount;
        }
    }

    public function pageCountByDefault($default)
    {
        self::pageCount($this->request->getQueryArguments(PAGE_COUNT));
        if (!self::pageCount()) {
            self::pageCount($default);
        }

        if ($default < 1) {
            self::pageCount(PAGE_COUNT_ZERO);
        }

        return self::pageCount();
    }

    public function setCacheFileName($value = '')
    {
        $this->cacheFileName = $value;
        if ($this->cacheFileName == '') {
            $this->cacheFileName = RUNTIME_DIR . strtolower(str_replace(DIRECTORY_SEPARATOR, '_', $this->getControllerFileName()));
        }
    }
    public function getCacheFileName()
    {
        return $this->cacheFileName;
    }

    public function getPhpCode(): string
    {
        if (!$this->code) {
            //        $this->code = $this->redis->mget($this->getCacheFileName());
            //        $this->code = $this->code[0];
            if (file_exists($this->getCacheFileName())) {
                $this->code = file_get_contents($this->getCacheFileName());
            }
        }

        return $this->code;
    }

    public function preHtmlExists()
    {
        return file_exists($this->getPreHtmlName());
    }

    public function getGlobalDesignName()
    {
        $parts = pathinfo($this->getFileName());
        return DOCUMENT_ROOT . 'tmp' . DIRECTORY_SEPARATOR . $parts['filename'] . '.design.php';
    }

    public function getJsonName()
    {
        return RUNTIME_DIR . $this->className . '.json';
    }

    public function getConfigName()
    {
        $parts = pathinfo($this->getFileName());
        return DOCUMENT_ROOT . 'config' . DIRECTORY_SEPARATOR . $parts['filename'] . '.config.' . $parts['extension'];
    }

    public function getXmlName()
    {
        $parts = pathinfo($this->getFileName());
        return DOCUMENT_ROOT . 'tmp' . DIRECTORY_SEPARATOR . $parts['filename'] . '.xml';
    }

    public function getMotherView(): ?TCustomControl
    {
        return $this->motherView;
    }

    public function getMotherUID(): string
    {
        return $this->motherUID;
    }

    public function getParentType()
    {
        return $this->parentType;
    }

    public function getAuthentication()
    {
        return $this->authentication;
    }

    public function setRedis(array $params): void
    {
        if(class_exists('Redis')) {

            // $this->redis = new Redis($params);
            $this->redis = null;
        }
       
    }

    public function getRedis(): ?object
    {
        return $this->redis;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getDirName(): string
    {
        return $this->dirName;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getActionName(): string
    {
        return $this->actionName;
    }

    public function getFileNamespace(): string
    {
        return $this->namespace;
    }

    public function getRawPhpName(): string
    {
        return $this->cacheFileName;
    }

    public function getModelFileName(): string
    {
        return $this->modelFileName;
    }

    public function getViewFileName(): string
    {
        return $this->viewFileName;
    }

    public function getControllerFileName(): string
    {
        return $this->controllerFileName;
    }

    public function getJsControllerFileName(): string
    {
        return $this->jsControllerFileName;
    }

    public function getCssFileName(): string
    {
        return $this->cssFileName;
    }

    public function getApplication(): TCustomApplication
    {
        return $this->application;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getCommands(): array
    {
        return $this->commands;
    }

    public function getViewName(): string
    {
        return $this->viewName;
    }

    public function setViewName($viewName = null): void
    {
        $uri = ($viewName === null) ? REQUEST_URI : $viewName;
        $requestUriParts = explode('/', $uri);
        $this->viewName = array_pop($requestUriParts);
        $viewNameParts = explode('.', $this->viewName);
        $this->viewName = array_shift($viewNameParts);

        $this->viewName = ($this->viewName == '') ? MAIN_VIEW : $this->viewName;
        $this->className = ucfirst($this->viewName);
    }

    public function getTwigEnvironment()
    {
        return $this->twigEnvironment;
    }

    public function renderTwig(array $dictionary = []): string
    {
        $result = '';

        if ($this->getTwigEnvironment() !== null) {
            $result = $this->getTwigEnvironment()->render($this->getViewName() . PREHTML_EXTENSION, $dictionary);
        }

        return $result;
    }

    public function renderTwigByName(string $viewName, array $dictionary = []): string
    {
        $result = '';

        if ($this->getTwigEnvironment() !== null) {
            $result = $this->getTwigEnvironment()->render($viewName, $dictionary);
        }

        return $result;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(): void
    {
        $this->namespace = $this->getFileNamespace();

        if (!isset($this->namespace)) {
            $this->namespace = \Phink\TAutoloader::getDefaultNamespace();
        }
    }

    public function isInternalView(): bool
    {
        return $this->viewIsInternal;
    }

    public function setNames(): void
    {
        $this->actionName = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';
        $this->modelFileName = 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . $this->viewName . CLASS_EXTENSION;
        $this->viewFileName = 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR  . $this->viewName . PREHTML_EXTENSION;
        $this->cssFileName = 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $this->viewName . CSS_EXTENSION;
        $this->controllerFileName = 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $this->viewName . CLASS_EXTENSION;
        $this->jsControllerFileName = 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $this->viewName . JS_EXTENSION;
        if ($this->isInternalView()) {
            $dirName = $this->getDirName();
            $this->viewFileName = $dirName . DIRECTORY_SEPARATOR  . $this->viewName . PREHTML_EXTENSION;
            $this->cssFileName = $dirName . DIRECTORY_SEPARATOR . $this->viewName . CSS_EXTENSION;
            $this->controllerFileName = $dirName . DIRECTORY_SEPARATOR . $this->viewName . CLASS_EXTENSION;
            $this->jsControllerFileName = $dirName . DIRECTORY_SEPARATOR . $this->viewName . JS_EXTENSION;
        }

        if (!file_exists($this->viewFileName)) {
            $info = TRegistry::classInfo($this->className);
            if ($info !== null) {
                // $this->viewName = \Phink\TAutoloader::classNameToFilename($this->className);
                $this->viewName = \Phink\TAutoloader::classNameToFilename($this->className);
                if ($info->path[0] == '@') {
                    $path = str_replace("@" . DIRECTORY_SEPARATOR, PHINK_VENDOR_APPS, $info->path);
                } else {
                    $path = PHINK_VENDOR_LIB . $info->path;
                }
                // $path = $info->path;
                if ($info->hasTemplate) {
                    $this->viewFileName = $path . $this->viewName . PREHTML_EXTENSION;
                } else {
                    $this->viewFileName = '';
                }
                $this->controllerFileName = $path . $this->viewName . CLASS_EXTENSION;
                $this->jsControllerFileName = $path . $this->viewName . JS_EXTENSION;
                $this->cssFileName = $path . $this->viewName . CSS_EXTENSION;
                $this->className = $info->namespace . '\\' . $this->className;
            }
        }

        TRegistry::write(
            $this->getUID(),
            [
                "parentUID" => ($this->getParent() !== null) ? $this->getParent()->getUID() : '',
                "id" => $this->getId(),
                "name" => $this->viewName,
                "view" => $this->viewFileName,
                "controller" => $this->controllerFileName,
                "css" => $this->cssFileName,
                "js" =>  $this->jsControllerFileName,
                "cache" =>
                [
                    "controller" => SRC_ROOT . TAutoloader::cacheFilenameFromView($this->viewName),
                    "css" => SRC_ROOT . TAutoloader::cacheCssFilenameFromView($this->viewName),
                    "js" =>  SRC_ROOT . TAutoloader::cacheJsFilenameFromView($this->viewName)
                ]
            ]
        );

        self::getLogger()->dump('MVC FILE NAMES FOR ' . $this->getUID(), TRegistry::item($this->getUID()));
        $this->setCacheFileName();
    }

    public function cloneNamesFrom($parent): void
    {
        $this->className = $parent->getClassName();
        $this->actionName = $parent->getActionName();
        $this->modelFileName = $parent->getModelFileName();
        $this->viewFileName = $parent->getViewFileName();
        $this->cssFileName = $parent->getCssFileName();
        $this->controllerFileName = $parent->getControllerFileName();
        $this->jsControllerFileName = $parent->getJsControllerFileName();
        $this->namespace = $parent->getNamespace();
    }
}
