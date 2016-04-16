<?php
namespace Phoenix\Web\UI\Widget\Plugin;

class TPlugin extends \Phoenix\Web\UI\TPluginRenderer
{
    use \Phoenix\Data\UI\TDataTag;
    
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
        $this->_rowCount = $this->pageCountByDefault($this->_rowCount);
        $this->hasChildren = count($this->_children) > 0;

        if($this->hasChildren) {
            $this->templates = $this->getControls($this->_children);
            $id = $this->getParent()->getId();
            
            $json = json_encode($this->templates);
            $templateFilename = TMP_DIR . DIRECTORY_SEPARATOR . $id . '_template.json';
            file_put_contents($templateFilename, $json);
            
            $this->_children = $this->assocArrayByAttribute($this->_children, 'name');
            $this->_childrenCount = count($this->_children);
        }
        else {
            $this->_childrenCount = $this->statement->getFieldCount();
        }
        
    }

    public static function getGridData($id, \Phoenix\Data\Client\PDO\TPdoCommand $cmd, $rowCount)
    {
        $templateFilename = TMP_DIR . DIRECTORY_SEPARATOR . $id . '_template.json';
        //\Phoenix\Log\TLog::debug('TEMPLATE FILE : ' . $templateFilename);
        $templates = '';
        if(file_exists($templateFilename)) {
            $templates = json_decode(file_get_contents($templateFilename));
        }
        
        $elementsFilename = TMP_DIR . DIRECTORY_SEPARATOR . $id . '_elements.json';
        //\Phoenix\Log\TLog::debug('TEMPLATE FILE : ' . $elementsFilename);
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
        
        //\Phoenix\Log\TLog::dump('RECORDSET VALUES', $values);
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
    }
    
    public function renderHtml()
    {
        $this->data = self::getGridData($this->getParent()->getViewName(), $this->command, $this->_rowCount);

        parent::renderHtml();

        $this->isRendered = true;
        
    }

}
