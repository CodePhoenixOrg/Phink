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
trait TCodeGenerator {
    //put your code here
    function writeDeclarations(TXmlDocument $doc, TCustomView $view)
    {

        $result = '';
        $matches = $doc->getMatchesByDepth();
        $docList = $doc->getList();
        $count = count($docList);

        $code = '';
        $uses = array();
        $requires = array();
        $creations = array();
        $additions = array();
        $afterBinding = array();

        $childName = array();
        $childrenIndex = array();
        
        $doc->getLogger()->debug('DOC LIST');
        $doc->getLogger()->debug($docList);
//        array_push($creations, array());
        
        $isFirst = true;
        foreach ($docList as $control) {
            
            if(in_array($control['name'], ['page', 'echo', 'exec', 'type']) || $control['method'] == 'render') {
                continue;
            }

            $className = '';
            $nameSpace = '';
            $classPath = '';
            $templatePath = '';
            $j = $control['id'];

            if(isset($control['properties'])) {

                $parentId = $control['parentId'];
                $parentControl = ($parentId > -1) ? $docList[$parentId] : array();
                if(isset($parentControl['childName'])) {
                    $parentChildName = $parentControl['childName'];
                    $controlName = $control['name'];

                    if($parentChildName == $controlName) {
                        if(!isset($childrenIndex["$parentId"])) {
                            $childrenIndex[$parentId] = 0;
                        }
                        else {
                            $childrenIndex[$parentId] = $childrenIndex[$parentId] + 1;
                        }
                    }
                }

                $properties = $control['properties'];
                $controlId = $properties['id'];
                //$className = ucfirst($control['name']);
                $className = $control['name'];
                $fqcn = '';
                $code  = '';
                $info = TRegistry::classInfo($className);
                //self::$logger->dump('REGISTRY INFO ' . $className, $info);
                if ($info) {
                    if(!$info->isAutoloaded) {
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

                    $class = \Phink\TAutoloader::includeClass($fullClassPath, RETURN_CODE | INCLUDE_FILE);
                    $fqcn = $class['type'];
                    $code = $class['code'];
                    
                    self::$logger->dump('FULL_QUALIFIED_CLASS_NAME: ', $fqcn);
                    
                    
                    $jsCode = '';
                    if(file_exists(DOCUMENT_ROOT . $fullJsCachePath)) {
                        $view->getResponse()->addScript($fullJsCachePath);
                    } else if(file_exists(SITE_ROOT . $fullJsClassPath)) {
                        $jsCtrlCode = file_get_contents(SITE_ROOT . $fullJsClassPath) . CR_LF;
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
                if(isset($childrenIndex[$parentId])) {
                    $index = '[' . $childrenIndex[$parentId] . ']';
                }

                $thisControl = '$this' . (($notThis) ? '->' . $controlId . $index : '');
                
                $creations[$j] = array();
                $additions[$j] = array();
                $afterBinding[$j] = array();
                if($isFirst) {
                    array_push($creations[$j], '$this->setId("' . $this->getViewName() . '"); ');
                    $isFirst = false;
                }
        
                foreach($properties as $key=>$value) {
                    if($key == 'id' ) {
                        if($serialize) array_push($creations[$j], 'if(!' . $thisControl . ' = \Phink\Core\TObject::wakeUp("' . $value . '")) {');
                        if ($notThis) {
                            array_push($creations[$j], $thisControl . ' = new \\' . $fqcn . '($this); ');
                        }

                        array_push($creations[$j], $thisControl . '->set' . ucfirst($key) . '("' . $value . '"); ');
                        
                        if($serialize) {
                            array_push($creations[$j], '}');
                            array_push($additions[$j], 'if(' . $thisControl . ' && !' . $thisControl . '->isAwake()) {');
                        }
                        continue;
                    }
                    if(is_numeric($value)) {
                        array_push($additions[$j], $thisControl . '->set' . ucfirst($key) . '(' . $value . '); ');
                    }
                    else {
                        $sa = explode(':', $value);
                        $member = '';
                        if(count($sa) > 1) {
                            $member = $sa[1];
                            if($sa[0] == 'var') {
                                //if($key == 'for') {
//                                    array_push($afterBinding[$j], $thisControl . '->set' . ucfirst($key) . '($this->' . $member . '); ');
//                                } else {
                                    array_push($additions[$j], $thisControl . '->set' . ucfirst($key) . '($this->' . $member . '); ');
//                                }
                            } elseif ($sa[0] == 'prop') {
                                array_push($additions[$j], $thisControl . '->set' . ucfirst($key) . '($this->get' . ucfirst($member) . '()); ');
                            }
                        }
                        else {
                            //if($key == 'command') {
//                                array_push($afterBinding[$j], $thisControl . '->set' . ucfirst($key) . '("' . $value . '"); ');
//                            } else {
                                array_push($additions[$j], $thisControl . '->set' . ucfirst($key) . '("' . $value . '"); ');
//                            }
                        }
                    }
                }
                if($serialize) {
                    array_push($additions[$j], $thisControl . '->sleep(); ');
                    array_push($additions[$j], '} ');
                }
                array_push($additions[$j], '$this->addChild(' . $thisControl . ');');
                
                $creations[$j] = implode(CR_LF, $creations[$j]);
                $additions[$j] = implode(CR_LF, $additions[$j]);
                $afterBinding[$j] = implode(CR_LF, $afterBinding[$j]);
            }

            
            $method = $docList[$j]['method'];
            if((TRegistry::classInfo($method)  && TRegistry::classCanRender($method)) || !TRegistry::classInfo($method))
            {
                $doc->fieldValue($j, 'method', 'render');
            }
        }

        $requires = array_unique($requires);
        $requires = implode(CR_LF, $requires);
        $uses = array_unique($uses);
        $uses = implode(CR_LF, $uses);

        $objectCreation = CR_LF;
        $objectCreation .= $requires . CR_LF;
        $objectCreation .= $uses . CR_LF;
        foreach($matches as $matchIndex) {
            if(!isset($creations[$matchIndex])) continue;
            $objectCreation .= $creations[$matchIndex] . CR_LF;
        }
        
        $objectAdditions = CR_LF;
        foreach($matches as $matchIndex) {
            if(!isset($additions[$matchIndex])) continue;
            $objectAdditions .= $additions[$matchIndex] . CR_LF;
        }

        $objectAfterBiding = CR_LF;
        foreach($matches as $matchIndex) {
            if(!isset($afterBinding[$matchIndex])) continue;
            $objectAfterBiding .= $afterBinding[$matchIndex] . CR_LF;
        }
        
        return (object)['creations' => $objectCreation, 'additions' => $objectAdditions, 'afterBinding' => $objectAfterBiding];
    }
    
    function writeHTML(TXmlDocument $doc, TCustomView $view)
    {
//        if(file_exists($this->jsControllerFileName) && !strstr($this->jsControllerFileName, 'main.js')) {
//            $pageCode = "<script data-getscript='itsme' src='" . ((HTTP_HOST !== SERVER_NAME) ? SERVER_HOST : SERVER_ROOT) . WEB_SEPARATOR . \Phink\Utils\TFileUtils::webPath($this->jsControllerFileName) . "'></script>" . CR_LF . $pageCode;        
//        }
        
        $pageCode = $view->getViewHtml();
               
        if(file_exists($this->cssFileName)) {
//            $pageCode = "<link rel='stylesheet' href='" . ((HTTP_HOST !== SERVER_NAME) ? SERVER_HOST : SERVER_ROOT) . "/" . $this->cssFileName . "' />" . CR_LF . $pageCode;
            $pageCode = "<script>TWebObject.getCSS('" . ((HTTP_HOST !== SERVER_NAME) ? SERVER_HOST : SERVER_ROOT) . WEB_SEPARATOR . \Phink\Utils\TFileUtils::webPath($this->cssFileName) . "');</script>" . CR_LF . $pageCode;        
        }
        
        $count = $doc->getCount();
        $matchesSort = $doc->getMatchesByDepth();
        $docList = $doc->getList();
        for($i = $count - 1; $i > -1; $i--)
        {
            $j = $matchesSort[$i];
            $match = new TXmlMatch($docList[$j]);

            $tag = $match->getMethod();
            $name = $match->getName();
            
            if($tag != 'echo' && $tag != 'exec' && $tag != 'render') {
                continue;
            }

            $type = $match->properties('type');
            $class = $match->properties('class');
            $id = $match->properties('id');

            $var = $match->properties('var');
            $prop = $match->properties('prop');
            $stmt = $match->properties('stmt');
            $params = $match->properties('params');

            if(!$type || $type == 'this') {
                $type = '$this->';
            }
            elseif($type == 'none') {
                $type = '';
            }
            else {
                $type = $type . '::' . (($tag == 'exec') ? '' : '$');
            }

            if($tag == 'echo' && $var) {
                $declare = '<?php echo ' . $type . $var . '; ?>';
            }
            elseif($tag == 'echo' && $prop) {
                $declare = '<?php echo ' . $type . 'get' . ucfirst($prop) . '(); ?>';
            }
            elseif($tag == 'exec') {
                $declare = '<?php echo ' . $type . $stmt . '(); ?>';
                if($params != NULL) {
                    $declare = '<?php echo ' . $type . $stmt . '(' . $params . '); ?>';
                }
            }
            elseif($tag == 'render') {
                if($name == 'this') {
                    $declare = '<?php $this->renderHtml(); $this->renderedHtml(); ?>';
                } else {
                    $declare = '<?php ' . $type . $id . '->render(); ?>';
                }
            }            

            $pageCode = TXmlDocument::replaceThisMatch($match, $pageCode, $declare);

        }
        return $pageCode;
    }
}
