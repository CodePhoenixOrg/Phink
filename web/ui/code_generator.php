<?php
namespace Phoenix\Web\UI;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Phoenix\Core\TRegistry;
use Phoenix\Xml\TXmlDocument;
use Phoenix\Xml\TXmlMatch;

/**
 * Description of code_generator
 *
 * @author David
 */
trait TCodeGenerator {
    //put your code here
    function writeDeclarations(TXmlDocument $doc)
    {

        $result = '';
        $matches = $doc->getMatchesByDepth();
        $docList = $doc->getList();
        $count = count($docList);

        $code = '';
        $uses = array();
        $requires = array();
        $additions = array();
        $creations = array();

        $childName = array();
        $childrenIndex = array();
        
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
                $className = $control['name'];
                $fqcn = '';
                $code  = '';
                $info = TRegistry::classInfo($className);
                \Phoenix\Log\TLog::dump('REGISTRY INFO ' . $className, $info);
                if ($info) {
                    if($info->canRender) {
                        array_push($requires, '\\Phoenix\\TAutoloader::import("' . $className . '");');
                    }
                    $fqcn = $info->namespace . '\\' . $className;
                } elseif ($className != 'this') {
                    $viewName = lcfirst($className);
                    $fullClassPath = 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $viewName . DIRECTORY_SEPARATOR . $viewName . CLASS_EXTENSION;
                    array_push($requires, '\\Phoenix\\TAutoloader::import("' . $viewName . '");');
                    $class = \Phoenix\TAutoloader::includeClass($fullClassPath);
                    $fqcn = $class['type'];
                    $code = $class['code'];
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
                if($isFirst) {
                    array_push($creations[$j], '$this->setId("' . $this->getViewName() . '"); ');
                    $isFirst = false;
                }
        
                foreach($properties as $key=>$value) {
                    if($key == 'id' ) {
                        if($serialize) array_push($creations[$j], 'if(!' . $thisControl . ' = \Phoenix\Core\TObject::wakeUp("' . $value . '")) {');
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
                                array_push($additions[$j], $thisControl . '->set' . ucfirst($key) . '($this->' . $member . '); ');
                            } elseif ($sa[0] == 'prop') {
                                array_push($additions[$j], $thisControl . '->set' . ucfirst($key) . '($this->get' . ucfirst($member) . '()); ');
                            }
                        }
                        else {
                            array_push($additions[$j], $thisControl . '->set' . ucfirst($key) . '("' . $value . '"); ');
                        }
                    }
                }
                if($serialize) {
                    array_push($additions[$j], $thisControl . '->sleep(); ');
                    array_push($additions[$j], '} ');
                }
                $creations[$j] = implode(CR_LF, $creations[$j]);
                $additions[$j] = implode(CR_LF, $additions[$j]);
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

        return (object)['creations' => $objectCreation, 'additions' => $objectAdditions];
    }
    
    function writeHTML(TXmlDocument $doc, $pageCode)
    {
                

        if(file_exists($this->jsControllerFileName)) {
            $pageCode = "<script src='" . ((HTTP_HOST !== SERVER_NAME) ? SERVER_HOST : SERVER_ROOT) . WEB_SEPARATOR . \Phoenix\Utils\TFileUtils::webPath($this->jsControllerFileName) . "'></script>" . CR_LF . $pageCode;        
        }
        if(file_exists($this->cssFileName)) {
//            $pageCode = "<link rel='stylesheet' href='" . ((HTTP_HOST !== SERVER_NAME) ? SERVER_HOST : SERVER_ROOT) . "/" . $this->cssFileName . "' />" . CR_LF . $pageCode;
            $pageCode = "<script>TWebObject.getCSS('" . ((HTTP_HOST !== SERVER_NAME) ? SERVER_HOST : SERVER_ROOT) . WEB_SEPARATOR . \Phoenix\Utils\TFileUtils::webPath($this->cssFileName) . "');</script>" . CR_LF . $pageCode;        
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
