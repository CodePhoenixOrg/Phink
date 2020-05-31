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

namespace Phink\MVC;

use Phink\Cache\TCache;
use Phink\Registry\TRegistry;
use Phink\TAutoloader;
use Phink\Web\IWebObject;
use Phink\Web\UI\TCustomControl;
use Phink\Xml\TXmlDocument;

abstract class TCustomView extends TCustomControl
{
    use \Phink\Web\UI\TCodeGenerator {
        writeDeclarations as private;
        writeHTML as private;
    }

    protected $router = null;
    protected $viewHtml = '';
    protected $twigHtml = '';
    protected $preHtml = '';
    protected $designs = array();
    protected $design = '';
    protected $creations = '';
    protected $additions = '';
    protected $afterBinding = '';
    protected $modelIsIncluded = false;
    protected $controllerIsIncluded = false;
    protected $pattern = '';
    protected $depth = 0;
    protected $viewIsMother = false;
    protected $engineIsReed = true;
    protected $engineIsTwig = false;

    function __construct(IWebObject $parent)
    {
        parent::__construct($parent);

        $this->clonePrimitivesFrom($parent);

        //$this->redis = new Client($this->context->getRedis());
    }

    function isMotherView(): bool
    {
        return $this->viewIsMother;
    }

    function isDirty(): bool
    {
        return $this->_dirty;
    }

    function isReedEngine(): bool
    {
        return $this->engineIsReed;
    }

    function isTwigEngine(): bool
    {
        return $this->engineIsTwig;
    }

    function getDepth(): int
    {
        return $this->depth;
    }
    function setDepth($value): void
    {
        $this->depth = $value;
    }

    function getCreations(): string
    {
        return $this->creations;
    }

    function getAdditions(): string
    {
        return $this->additions;
    }

    function getAfterBinding(): string
    {
        return $this->afterBinding;
    }

    // public function setViewHtml($html)
    // {
    //     $this->viewHtml = $html;
    // }

    function getViewHtml(): string
    {
        return $this->viewHtml;
    }

    function setTwigHtml($html): void
    {
        $this->twigHtml = $html;
    }

    function getTwigHtml(): string
    {
        return $this->twigHtml;
    }

    function setTwig(array $dictionary): void
    {
        $viewName = $this->getViewName() . PREHTML_EXTENSION;
        $this->setTwigByName($viewName, $dictionary);
    }

    function setTwigByName(string $viewName, array $dictionary): void
    {
        $html = $this->renderTwigByName($viewName, $dictionary);
        $this->twigHtml = $html;

        $this->engineIsTwig = true;
        $this->engineIsReed = false;
    }

    function loadView($filename): string
    {
        $lines = file($filename);
        $text = '';
        foreach ($lines as $line) {
            // $text .= trim($line) . PHP_EOL;
            $text .= $line;
        }

        return $text;
    }

    function parse(): bool
    {
        // self::getLogger()->debug($this->viewName . ' IS REGISTERED : ' . (TRegistry::exists('code', $this->controllerFileName) ? 'TRUE' : 'FALSE'), __FILE__, __LINE__);

        /** LATER FOR REDIS
         * $this->viewHtml = $this->redis->mget($templateName);
         * $this->viewHtml = $this->viewHtml[0];
         */

        $baseViewDir = SITE_ROOT;

        while (empty($this->getViewHtml())) {
            if (file_exists(SRC_ROOT . $this->viewFileName) && !empty($this->viewFileName)) {
                // self::getLogger()->debug('PARSE SRC ROOT FILE : ' . $this->viewFileName, __FILE__, __LINE__);

                $baseViewDir = SRC_ROOT;
                $this->viewHtml = file_get_contents($baseViewDir . $this->viewFileName);

                continue;
            }
            if (file_exists(SITE_ROOT . $this->viewFileName) && !empty($this->viewFileName)) {
                // self::getLogger()->debug('PARSE SITE ROOT FILE : ' . $this->viewFileName, __FILE__, __LINE__);

                $this->viewHtml = file_get_contents($baseViewDir . $this->viewFileName);

                continue;
            }

            if (SITE_ROOT . $this->getDirName() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $this->viewName . PREHTML_EXTENSION == $this->getPath()) {

                $this->viewFileName = $this->getPath();
                if ($this->viewFileName[0] == '@') {
                    $this->viewFileName = str_replace("@" . DIRECTORY_SEPARATOR, '', $this->viewFileName);
                }
                $this->viewHtml = file_get_contents($baseViewDir . $this->viewFileName);

                continue;
            }

            break;
        }

        $fullViewDir = $baseViewDir . pathinfo($this->viewFileName, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR;

        $head = $this->getStyleSheetTag();
        $script = $this->getScriptTag();

        if ($this->isMotherView()) {
            if ($head !== null) {
                TRegistry::push($this->getMotherUID(), 'head', $head);
                $this->appendToHead($head, $this->viewHtml);
            }
            if ($script !== null) {
                TRegistry::push($this->getMotherUID(), 'scripts', $script);
                $this->appendToBody($script, $this->viewHtml);
            }
        }

        $doc = new TXmlDocument($this->viewHtml);
        $doc->matchAll();

        $firstMatch = $doc->getNextMatch();
        if ($firstMatch !== null && $firstMatch->getMethod() === 'extends') {

            $masterFilename = $firstMatch->properties('template');
            $masterViewName = pathinfo($masterFilename, PATHINFO_FILENAME);
            $masterHtml = file_get_contents($fullViewDir . $masterFilename);

            $masterDoc = new TXmlDocument($masterHtml);
            $masterDoc->matchAll();

            $this->viewHtml = $masterDoc->replaceMatches($doc, $this->viewHtml);

            $masterHead = $this->getStyleSheetTag($masterViewName, false);
            $masterScript = $this->getScriptTag($masterViewName, false);

            if ($masterHead !== null) {
                $this->appendToHead($masterHead, $this->viewHtml);
            }
            if ($masterScript !== null) {
                $this->appendToBody($masterScript, $this->viewHtml);
            }

            $doc = new TXmlDocument($this->viewHtml);
            $doc->matchAll();

        }
        
        if ($doc->getCount() > 0) {
            $declarations = $this->writeDeclarations($doc, $this);
            $this->creations = $declarations->creations;
            $this->additions = $declarations->additions;
            $this->afterBinding = $declarations->afterBinding;
            $this->viewHtml = $this->writeHTML($doc, $this);
        }

        TRegistry::setHtml($this->getUID(), $this->viewHtml);

        if (!TRegistry::exists('code', $this->getUID())) {
            // self::getLogger()->debug('NO NEED TO WRITE CODE: ' . $this->controllerFileName, __FILE__, __LINE__);
            return false;
        }

        $code = TRegistry::getCode($this->getUID());
        // We store the parsed code in a file so that we know it's already parsed on next request.
        $code = str_replace(CREATIONS_PLACEHOLDER, $this->creations, $code);
        $code = str_replace(ADDITIONS_PLACEHOLDER, $this->additions, $code);
        if (!$this->isMotherView() || $this->getRequest()->isAJAX()) {
            $code = str_replace(HTML_PLACEHOLDER, $this->viewHtml, $code);
        }
        $code = str_replace(DEFAULT_CONTROLLER, DEFAULT_CONTROL, $code);
        $code = str_replace(DEFAULT_PARTIAL_CONTROLLER, DEFAULT_PARTIAL_CONTROL, $code);
        $code = str_replace(CONTROLLER, CONTROL, $code);
        $code = str_replace(PARTIAL_CONTROLLER, PARTIAL_CONTROL, $code);
        if (!empty(trim($code))) {
            self::getLogger()->debug('SOMETHING TO CACHE : ' . $this->getCacheFileName(), __FILE__, __LINE__);
            if (!$this->isMotherView()) {
                file_put_contents($this->getCacheFileName(), $code);
            }
            TRegistry::setCode($this->getUID(), $code);
        }

        $this->engineIsReed = true;

        /** LATER FOR REDIS
         * $this->redis->mset($this->preHtmlName, $this->declarations . $this->viewHtml);
         */
        self::register($this);

        // We generate the code, but we don't flag it as parsed because it was not "executed"
        return false;
    }

    function safeCopy(string $filename, string $cacheFilename): bool
    {
        $ok = false;
        $src = SRC_ROOT . $filename;
        $dest = DOCUMENT_ROOT . $cacheFilename;

        if (!file_exists($src)) {
            $src = SITE_ROOT . $filename;
        }

        if (file_exists($src)) {
            $ok = file_exists($dest);
            if (!$ok) {
                $ok = copy($src, $dest);
                self::getLogger()->debug("copy(" . $src . ", " . $dest . ")");
            }
        }

        return $ok;
    }

    function getScriptTag(?string $viewName = null, ?bool $isInternal = null): ?string
    {
        $jsControllerFileName = '';

        if ($viewName !== null) {
            $mvc = $this->getMvcFileNamesByViewName($viewName);
            $jsControllerFileName = $mvc['jsControllerFileName'];
        }

        if ($viewName === null) {
            $jsControllerFileName = $this->getJsControllerFileName();
            $viewName = $this->getViewName();
        }
        if ($isInternal === null) {
            $isInternal = $this->isInternalComponent();
        }

        $cacheJsFilename = TCache::cacheJsFilenameFromView($viewName, $isInternal);
        $script = "<script src='" . TAutoloader::absoluteURL($cacheJsFilename) . "'></script>" . PHP_EOL;

        $ok = $this->safeCopy($jsControllerFileName, $cacheJsFilename);

        return ($ok) ? $script : null;
    }

    function getStyleSheetTag(?string $viewName = null, ?bool $isInternal = null): ?string
    {
        $cssFileName = '';

        if ($viewName !== null) {
            $mvc = $this->getMvcFileNamesByViewName($viewName);
            $cssFileName = $mvc['cssFileName'];
        }

        if ($viewName === null) {
            $cssFileName = $this->getCssFileName();
            $viewName = $this->getViewName();
        }
        if ($isInternal === null) {
            $isInternal = $this->isInternalComponent();
        }

        $cacheCssFilename = TCache::cacheCssFilenameFromView($viewName, $isInternal);
        $head = "<link rel='stylesheet' href='" . TAutoloader::absoluteURL($cacheCssFilename) . "' />" . PHP_EOL;

        $ok = $this->safeCopy($cssFileName, $cacheCssFilename);

        return ($ok) ? $head : null;
    }

    function appendToBody(string $scripts, string &$viewHtml): void
    {
        if ($scripts !== '') {
            $scripts .= '</body>' . PHP_EOL;
            $viewHtml = str_replace('</body>', $scripts, $viewHtml);
        }
    }

    function appendToHead(string $head, string &$viewHtml): void
    {
        if ($head !== '') {
            $head .= '</head>' . PHP_EOL;
            $viewHtml = str_replace('</head>', $head, $viewHtml);
        }
    }

    function register(IWebObject $object): void
    {
        TRegistry::write(
            $object->getUID(),
            [
                "id" => $object->getId(),
                "name" => $object->getViewName(),
                "UID" => $object->getUID(),
                "parentUID" => ($object->getParent() !== null) ? $object->getParent()->getUID() : '',
                "isMotherView" => ($object->isMotherView()) ? "true" : "false",
                "view" => $object->getViewFileName(),
                "controller" => $object->getControllerFileName(),
                "css" => $object->getCssFileName(),
                "js" => $object->getJsControllerFileName(),
                "cache" =>
                [
                    "controller" => SRC_ROOT . TCache::cacheFilenameFromView($object->getViewName(), $this->isInternalComponent()),
                    "css" => SRC_ROOT . TCache::cacheCssFilenameFromView($object->getViewName(), $this->isInternalComponent()),
                    "js" => SRC_ROOT . TCache::cacheJsFilenameFromView($object->getViewName(), $this->isInternalComponent()),
                ],
            ]
        );
    }
}
