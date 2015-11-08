<?php
namespace Phoenix\Data\UI;

class TDataGrid extends \Phoenix\Web\UI\TGrid
{
    use TDataTag;
    
    private $_columns = array();
    private $_rowCount;
    private $_pageNum;
    private $_columnCount;
    protected $contents = NULL;
    protected $hasColumns = false;

    public function getColumns()
    {
        return $this->_columns;
    }
    public function setColumns($value)
    {
        $this->_columns = $value;
    }
    
    public function getColumnCount()
    {
        return $this->_columnCount;
    }
    public function setColumnCount($value)
    {
        $this->_columnCount = $value;
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
        $this->hasColumns = count($this->_columns) > 0;

        if($this->hasColumns) {
            $this->templates = $this->getControls($this->_columns);
            $json = json_encode($this->templates);
            $id = $this->getParent()->getId();
            $templateFilename = TMP_DIR . DIRECTORY_SEPARATOR . $id . '_template.json';
            file_put_contents($templateFilename, $json);
            $this->_columns = $this->assocArrayByAttribute($this->_columns, 'name');
            
            $this->_columnCount = count($this->_columns);
        }
        else {
            $this->_columnCount = $this->statement->getFieldCount();
        }
        
    }

    public function setData()
    {
        //$id = $this->parent->getId();
        //$id = $this->getParent()->getId();
        $id = $this->getParent()->getViewName();
        \Phoenix\Log\TLog::dump('DATA GRID PARENT', $this->parent);
        $this->data = self::getGridData($id, $this->command, $this->_rowCount);
    }
    
    public static function getGridData($id, \Phoenix\Data\Client\PDO\TPdoCommand $cmd, $rowCount)
    {
        $templateFilename = TMP_DIR . DIRECTORY_SEPARATOR . $id . '_template.json';
        \Phoenix\Log\TLog::debug('TEMPLATE FILE : ' . $templateFilename);
        $templates = '';
        if(file_exists($templateFilename)) {
            $templates = json_decode(file_get_contents($templateFilename));
//            \Phoenix\Log\TLog::dump('TEMPLATES 1', var_export($templates, true));
            //$templates = \Phoenix\Core\TObject::arraysToObjects($templates);
//            \Phoenix\Log\TLog::dump('TEMPLATES 2', var_export($templates, true));
        }
        
//        $sql = $cmd->getSelectQuery();
//        
//        
//        \Phoenix\Log\TLog::dump('SQL RENDER HTML', $sql);
//        //$this->command->setSelectQuery($sql);
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
        
        \Phoenix\Log\TLog::dump('RECORDSET VALUES', $values);
        return [
            'cols' => $fieldCount
            , 'rows' => $rowCount
            , 'names' => $stmt->getFieldNames()
            , 'templates' => $templates
            , 'values' => $values
        ];

    }
    
    public function getData() 
    {
        $this->setData();
        $this->response->setData('data', $this->data);
    }
    
    public function renderHtml()
    {
        $this->setData();

        parent::renderHtml();

        $this->isRendered = true;
        
    }

}


