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
 
 namespace Phink\Web\UI;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Phink\Core\TRegistry;
use Phink\Xml\TXmlDocument;
use Phink\Xml\TXmlMatch;
use Phink\MVC\TCustomView;

/**
 * Description of code_generator
 *
 * @author David
 */
trait TCodeGenerator
{
    //put your code here
    public function writeDeclarations(TXmlDocument $doc, TCustomView $view)
    {
        $result = '';
        $matches = $doc->getMatchesByDepth();
        $docList = $doc->getList();
        $count = count($docList);

        $code = '';
        $uses = [];
        $requires = [];
        $creations = [];
        $additions = [];
        $afterBinding = [];

        $childName = [];
        $childrenIndex = [];
        
        // $doc->getLogger()->debug('DOC LIST');
        // $doc->getLogger()->debug($docList);
//        array_push($creations, []);
        
        $isFirst = true;
        foreach ($docList as $control) {
            if (in_array($control['name'], ['page', 'echo', 'exec', 'type']) || $control['method'] == 'render') {
                continue;
            }

            $className = '';
            $nameSpace = '';
            $classPath = '';
            $templatePath = '';
            $j = $control['id'];

            if (isset($control['properties'])) {
                $parentId = $control['parentId'];
                $parentControl = ($parentId > -1) ? $docList[$parentId] : [];
                if (isset($parentControl['childName'])) {
                    $parentChildName = $parentControl['childName'];
                    $controlName = $control['name'];

                    if ($parentChildName == $controlName) {
                        if (!isset($childrenIndex["$parentId"])) {
                            $childrenIndex[$parentId] = 0;
                        } else {
                            $childrenIndex[$parentId] = $childrenIndex[$parentId] + 1;
                        }
                    }
                }

                // self::$logger->debug(print_r($control['properties'], true) . PHP_EOL);

                $properties = $control['properties'];
                $controlId = $properties['id'];
                //$className = ucfirst($control['name']);
                $className = $control['name'];
                $fqcn = '';
                $code  = '';
                $info = TRegistry::classInfo($className);
                //self::$logger->dump('REGISTRY INFO ' . $className, $info);
                if ($info) {
                    if (!$info->isAutoloaded) {
                        array_push($requires, '\\Phink\\TAutoloader::import($this, "' . $className . '");');
//                        array_push($requires, '$this->import("' . $className . '");');
                    }
                    $fqcn = $info->namespace . '\\' . $className;
                } elseif ($className !== 'this') {
                    $viewName = lcfirst($className);
                    $fullClassPath = 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $viewName . CLASS_EXTENSION;
                    $fullJsClassPath = 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $viewName . JS_EXTENSION;
                    $fullJsCachePath = \Phink\TAutoloader::cacheJsFilenameFromView($viewName);
                    array_push($requires, '\\Phink\\TAutoloader::import($this, "' . $className . '");');

                    self::$logger->dump('FULL_CLASS_PATH', $fullClassPath);

                    $class = \Phink\TAutoloader::includeClass($fullClassPath, RETURN_CODE);
                    $fqcn = $class['type'];
                    $code = $class['code'];
                    
                    self::$logger->dump('FULL_QUALIFIED_CLASS_NAME: ', $fqcn);
                    
                    
                    $jsCode = '';
                    if (file_exists(DOCUMENT_ROOT . $fullJsCachePath)) {
                        $view->getResponse()->addScript($fullJsCachePath);
                    } elseif (file_exists(SRC_ROOT . $fullJsClassPath)) {
                        $jsCtrlCode = file_get_contents(SRC_ROOT . $fullJsClassPath) . PHP_EOL;
                        file_put_contents(DOCUMENT_ROOT . $fullJsCachePath, $jsCtrlCode);
                        $view->getResponse()->addScript($fullJsCachePath);
                    }
                    TRegistry::setCode($fullClassPath, $code);
                }
            
                $canRender = ($info && $info->canRender || !$info);
                $notThis = ($className != 'this');
                $serialize = $canRender && $notThis;
                $serialize = false;
                $index = '';
                if (isset($childrenIndex[$parentId])) {
                    $index = '[' . $childrenIndex[$parentId] . ']';
                }

                $thisControl = '$this' . (($notThis) ? '->' . $controlId . $index : '');
                
                $creations[$j] = [];
                $additions[$j] = [];
                $afterBinding[$j] = [];
                if ($isFirst) {
                    array_push($creations[$j], '$this->setId("' . $this->getViewName() . '"); ');
                    $isFirst = false;
                }
        
                foreach ($properties as $key=>$value) {
                    // self::$logger->debug(print_r([$key, $value], true) . PHP_EOL);
                    if ($key == 'id') {
                        if ($serialize) {
                            array_push($creations[$j], 'if(!' . $thisControl . ' = \Phink\Core\TObject::wakeUp("' . $value . '")) {');
                        }
                        if ($notThis) {
                            array_push($creations[$j], $thisControl . ' = new \\' . $fqcn . '($this); ');
                        }

                        array_push($creations[$j], $thisControl . '->set' . ucfirst($key) . '("' . $value . '"); ');
                        
                        if ($serialize) {
                            array_push($creations[$j], '}');
                            array_push($additions[$j], 'if(' . $thisControl . ' && !' . $thisControl . '->isAwake()) {');
                        }
                        continue;
                    }
                    if (is_numeric($value)) {
                        array_push($additions[$j], $thisControl . '->set' . ucfirst($key) . '(' . $value . '); ');
                        continue;
                    }
                    if (strpos($value, ':') > -1) {
                        $sa = explode(':', $value);
                        $member = $sa[1];
                        if ($sa[0] == 'var') {
                            //if($key == 'for') {
//                                    array_push($afterBinding[$j], $thisControl . '->set' . ucfirst($key) . '($this->' . $member . '); ');
//                                } else {
                            array_push($additions[$j], $thisControl . '->set' . ucfirst($key) . '($this->' . $member . '); ');
//                                }
                        } elseif ($sa[0] == 'prop') {
                            array_push($additions[$j], $thisControl . '->set' . ucfirst($key) . '($this->get' . ucfirst($member) . '()); ');
                        }
                        continue;
                    }
                    if(!empty(strstr($value, '!#base64#'))) {
                        $plaintext = substr($value, 9);
                        $plaintext = \base64_decode($plaintext);
                        array_push($additions[$j], $thisControl . '->set' . ucfirst($key) . '(<<<PLAIN_TEXT' . PHP_EOL . $plaintext . PHP_EOL . 'PLAIN_TEXT' . PHP_EOL . '); ');
                        unset($value);
                        continue;
                    }
                    // if ($key == 'command') {
                    //     array_push($afterBinding[$j], $thisControl . '->set' . ucfirst($key) . '("' . $value . '"); ');
                    // } else {
                    array_push($additions[$j], $thisControl . '->set' . ucfirst($key) . '("' . $value . '"); ');
                    // }
                }
                if ($serialize) {
                    array_push($additions[$j], $thisControl . '->sleep(); ');
                    array_push($additions[$j], '} ');
                }
                array_push($additions[$j], '$this->addChild(' . $thisControl . ');');
                
                $creations[$j] = implode(PHP_EOL, $creations[$j]);
                $additions[$j] = implode(PHP_EOL, $additions[$j]);
                $afterBinding[$j] = implode(PHP_EOL, $afterBinding[$j]);
            }

            
            $method = $docList[$j]['method'];
            if ((TRegistry::classInfo($method)  && TRegistry::classCanRender($method)) || !TRegistry::classInfo($method)) {
                $doc->fieldValue($j, 'method', 'render');
            }
        }

        $requires = array_unique($requires);
        $requires = implode(PHP_EOL, $requires);
        $uses = array_unique($uses);
        $uses = implode(PHP_EOL, $uses);

        $objectCreation = PHP_EOL;
        $objectCreation .= $requires . PHP_EOL;
        $objectCreation .= $uses . PHP_EOL;
        foreach ($matches as $matchIndex) {
            if (!isset($creations[$matchIndex])) {
                continue;
            }
            $objectCreation .= $creations[$matchIndex] . PHP_EOL;
        }
        
        $objectAdditions = PHP_EOL;
        foreach ($matches as $matchIndex) {
            if (!isset($additions[$matchIndex])) {
                continue;
            }
            $objectAdditions .= $additions[$matchIndex] . PHP_EOL;
        }

        $objectAfterBiding = PHP_EOL;
        foreach ($matches as $matchIndex) {
            if (!isset($afterBinding[$matchIndex])) {
                continue;
            }
            $objectAfterBiding .= $afterBinding[$matchIndex] . PHP_EOL;
        }
        
        return (object)['creations' => $objectCreation, 'additions' => $objectAdditions, 'afterBinding' => $objectAfterBiding];
    }
    
    public function writeHTML(TXmlDocument $doc, TCustomView $view)
    {
        $viewHtml = $view->getViewHtml();
        $scripts = '';
        $head = '';

        if (file_exists(SRC_ROOT . $view->getJsControllerFileName()) && !strstr($view->getJsControllerFileName(), 'main.js') && $view->getType() == 'TView') {
            $cacheJsFilename = \Phink\TAutoloader::cacheJsFilenameFromView($view->getViewName());
            if (!file_exists(DOCUMENT_ROOT . $cacheJsFilename)) {
                copy(SRC_ROOT . $this->getJsControllerFileName(), DOCUMENT_ROOT . $cacheJsFilename);
            }
            // \Phink\Utils\TFileUtils::webPath($view->getCssFileName())
            $scripts = "<script src='" . ((HTTP_HOST !== SERVER_NAME) ? SERVER_HOST : SERVER_ROOT) . WEB_SEPARATOR . $cacheJsFilename . "'></script>" . PHP_EOL;
        }
        if (file_exists(SITE_ROOT . $view->getJsControllerFileName()) && !strstr($view->getJsControllerFileName(), 'main.js') && $view->getType() == 'TView') {
            $cacheJsFilename = \Phink\TAutoloader::cacheJsFilenameFromView($view->getViewName());
            if (!file_exists(DOCUMENT_ROOT . $cacheJsFilename)) {
                copy(SITE_ROOT . $this->getJsControllerFileName(), DOCUMENT_ROOT . $cacheJsFilename);
            }
            // \Phink\Utils\TFileUtils::webPath($view->getCssFileName())
            $scripts = "<script src='" . ((HTTP_HOST !== SERVER_NAME) ? SERVER_HOST : SERVER_ROOT) . WEB_SEPARATOR . $cacheJsFilename . "'></script>" . PHP_EOL;
        }

        if (file_exists(SRC_ROOT . $view->getCssFileName()) && $view->getType() == 'TView') {
            $cacheCssFilename = \Phink\TAutoloader::cacheCssFilenameFromView($view->getViewName());
            if (!file_exists(DOCUMENT_ROOT . $cacheCssFilename)) {
                copy(SRC_ROOT . $view->getCssFileName(), DOCUMENT_ROOT . $cacheCssFilename);
            }
            //\Phink\Utils\TFileUtils::webPath($view->getCssFileName()
            // $scripts .= "<script>Phink.Web.Object.getCSS('" . ((HTTP_HOST !== SERVER_NAME) ? SERVER_HOST : SERVER_ROOT) . WEB_SEPARATOR . $cacheCssFilename . "');</script>" . PHP_EOL;
            $head = "<link rel='stylesheet' href='" . ((HTTP_HOST !== SERVER_NAME) ? SERVER_HOST : SERVER_ROOT) . WEB_SEPARATOR . $cacheCssFilename . "' />" . PHP_EOL;
        }
        if (file_exists(SITE_ROOT . $view->getCssFileName()) && $view->getType() == 'TView') {
            $cacheCssFilename = \Phink\TAutoloader::cacheCssFilenameFromView($view->getViewName());
            if (!file_exists(DOCUMENT_ROOT . $cacheCssFilename)) {
                copy(SITE_ROOT . $view->getCssFileName(), DOCUMENT_ROOT . $cacheCssFilename);
            }
            //\Phink\Utils\TFileUtils::webPath($view->getCssFileName()
            // $scripts .= "<script>Phink.Web.Object.getCSS('" . ((HTTP_HOST !== SERVER_NAME) ? SERVER_HOST : SERVER_ROOT) . WEB_SEPARATOR . $cacheCssFilename . "');</script>" . PHP_EOL;
            $head = "<link rel='stylesheet' href='" . ((HTTP_HOST !== SERVER_NAME) ? SERVER_HOST : SERVER_ROOT) . WEB_SEPARATOR . $cacheCssFilename . "' />" . PHP_EOL;
        }

        if ($scripts !== '') {
            $scripts .= '</body>' . PHP_EOL;
            $viewHtml = str_replace('</body>', $scripts, $viewHtml);
        }

        if ($head !== '') {
            $head .= '</head>' . PHP_EOL;
            $viewHtml = str_replace('</head>', $head, $viewHtml);
        }

        $count = $doc->getCount();
        $matchesSort = $doc->getMatchesByDepth();
        $docList = $doc->getList();
        for ($i = $count - 1; $i > -1; $i--) {
            $j = $matchesSort[$i];
            $match = new TXmlMatch($docList[$j]);

            $tag = $match->getMethod();
            $name = $match->getName();
            
            if ($tag != 'echo' && $tag != 'exec' && $tag != 'render') {
                continue;
            }

            $type = $match->properties('type');
            $class = $match->properties('class');
            $id = $match->properties('id');

            $var = $match->properties('var');
            $prop = $match->properties('prop');
            $stmt = $match->properties('stmt');
            $params = $match->properties('params');

            if (!$type || $type == 'this') {
                $type = '$this->';
            } elseif ($type == 'none') {
                $type = '';
            } else {
                $type = $type . '::' . (($tag == 'exec') ? '' : '$');
            }

            if ($tag == 'echo' && $var) {
                $declare = '<?php echo ' . $type . $var . '; ?>';
            } elseif ($tag == 'echo' && $prop) {
                $declare = '<?php echo ' . $type . 'get' . ucfirst($prop) . '(); ?>';
            } elseif ($tag == 'exec') {
                $declare = '<?php echo ' . $type . $stmt . '(); ?>';
                if ($params != null) {
                    $declare = '<?php echo ' . $type . $stmt . '(' . $params . '); ?>';
                }
            } elseif ($tag == 'render') {
                if ($name == 'this') {
                    $declare = '<?php $this->renderHtml(); $this->renderedHtml(); ?>';
                } else {
                    $declare = '<?php ' . $type . $id . '->render(); ?>';
                }
            }

            $viewHtml = $doc->replaceThisMatch($match, $viewHtml, $declare);
        }
        return $viewHtml;
    }
}
