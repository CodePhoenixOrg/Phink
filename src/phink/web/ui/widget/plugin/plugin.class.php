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
 
 namespace Phink\Web\UI\Widget\Plugin;

class TPlugin extends \Phink\Web\UI\TPluginRenderer
{
    use \Phink\Data\UI\TDataTag;
    
    private $_children = array();
    private $_rowCount;
    private $_pageNum;
    private $_childrenCount;
    protected $contents = NULL;
    protected $hasChildren = false;

    public function getChildren()
    {
        return $this->_children;
    }
    public function setChildren($value)
    {
        $this->_children = $value;
    }
    
    public function getChildrenCount()
    {
        return $this->_childrenCount;
    }
    
    public function getRowCount()
    {
        return $this->_rowCount;
    }
    public function setRowCount($value)
    {
        $this->_rowCount = $value;
    }
    
    public function getPageNum()
    {
        return $this->_pageNum;
    }
    public function setPageNum($value)
    {
        $this->_pageNum = $value;
    }

    public function init()
    {
        $childrenCount = count($this->_children);
        $this->hasChildren = $childrenCount > 0;

        if($this->hasChildren) {
            $this->templates = $this->getControls($this->_children);
            $id = $this->getParent()->getId();
            
            $json = json_encode($this->templates);
            $templateFilename = RUNTIME_JS_DIR . $id . '_template.json';
            file_put_contents($templateFilename, $json);
            
            $this->_children = $this->assocArrayByAttribute($this->_children, 'name');
            $this->_childrenCount = count($this->_children);
        }
        else {
            $this->_childrenCount = $this->statement->getFieldCount();
        }
        
        $this->_rowCount = $this->pageCountByDefault($this->_rowCount);
//        if($this->_rowCount === 0) {
//            $this->_rowCount = $childrenCount;
//        }
    }

    public static function getGridData($id, \Phink\Data\Client\TCustomCommand $cmd, $rowCount = 1)
    {
        $templateFilename = RUNTIME_JS_DIR . $id . '_template.json';
        //self::$logger->debug('TEMPLATE FILE : ' . $templateFilename);
        $templates = '';
        if(file_exists($templateFilename)) {
            $templates = json_decode(file_get_contents($templateFilename));
        }
        
        $elementsFilename = RUNTIME_JS_DIR . $id . '_elements.json';
        //self::$logger->debug('TEMPLATE FILE : ' . $elementsFilename);
        $elements = '';
        if(file_exists($elementsFilename)) {
            $elements = json_decode(file_get_contents($elementsFilename));
        }
        
        $stmt = $cmd->querySelect();
        
        $fieldCount = $stmt->getFieldCount();
                
        $values = array();
        while($row = $stmt->fetch()) {
            $realRow = array();
            foreach($row as $col) {
                array_push($realRow, ($col));
            }
            array_push($values, json_encode($realRow));
        }
        $r = count($values);
        for($k = $r; $k < $rowCount; $k++) {
            array_push($values, json_encode(array_fill(0, $fieldCount, '&nbsp;')));
        }
//        if($rowCount < 1) {
            $rowCount = $r;
//        }
        
        //self::$logger->dump('RECORDSET VALUES', $values);
        $result = [
            'cols' => $fieldCount
            , 'rows' => $rowCount
            , 'names' => $stmt->getFieldNames()
            , 'values' => $values
            , 'templates' => $templates
        ];

        
        if(isset($elements)) {
            $result['elements'] = $elements;
        }
        
        return $result;

    }
    
    public function getData() 
    {
        $this->data = self::getGridData($this->getParent()->getViewName(), $this->command, $this->_rowCount);
        $this->response->setData('data', $this->data);
        
        //if(!$this->getRequest()->isAjax()) {
        //}
    }
    
    public function renderHtml()
    {
        $this->data = self::getGridData($this->getParent()->getViewName(), $this->command, $this->_rowCount);
    
        $id = $this->getParent()->getViewName();
        $scriptFilename = $id . '_data.js';
        $json = json_encode($this->data);
        file_put_contents(RUNTIME_JS_DIR . $scriptFilename, 'var ' . $id . 'Data = ' . $json . ';');
        
        $this->response->addScript(REL_RUNTIME_JS_DIR . $scriptFilename);

        parent::renderHtml();

        $this->isRendered = true;
        
    }

}
