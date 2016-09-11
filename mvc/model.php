<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of model
 *
 * @author david
 */
namespace Phink\MVC;

use Phink\Core\TObject;

interface IModel {
    
    function getSelectQuery();
    function getInsertQuery();
    function getUpdateQuery();
    function getDeleteQuery();
    function insert();
    function update();
    function delete();
    function save();
    function setTransaction($set);
}

class TModel extends TObject implements IModel
{
    use \Phink\Data\TCrudQueries;
    //put your code here
    protected $isNew = false;
    protected $isDirty = false;
    protected $isFromDB = false;
    protected $setTransaction = true;
    protected $toWincaramy = false;
    protected $dbConnector = null;
    protected $connector = null;
    protected $index = -1;
    protected $queries = null;

    public function __construct(TObject $parent = null)
    {
        //parent::setParent($parent);
        $this->isNew = true;
        $this->init();
    }

    public function init() {}
    
    public function getDbConnector()
    {
        return $this->dbConnector;
    }
    public function setDbConnector(IConnector $value)
    {
        $this->dbConnector = $value;
    }
    
    public function select() {}
    
    public function insert() {}
    
    public function update() {}

    public function delete() {}

    public function getInsertId()
    {
        return -1;
    }

    public function isNew()
    {
        return $this->isNew;
    }

    public function isDirty()
    {
        return $this->isDirty;
    }
    
    public function isFromDB()
    {
        return $this->isFromDB;
    }

    public function getIndex()
    {
        return $this->index;
    }
    
    public function setIndex($value)
    {
        $this->index = $value;
    }
            
    public function setTransaction($set = true)
    {
        $this->setTransaction = $set;
    }
    
    public function setDirty()
    {
        $this->isDirty = true;
        $this->isNew = false;
    }
    
    public function setFromDB($set = true)
    {
        $this->isFromDB = $set;
        if($set) {
            $this->isNew = !$set;
        }
    }

    public function save()
    {
        $result = false;
        
        if(!$this->isDirty) return $result;
        
        if($result = TConnector::beginTransaction()) {
        
            if($this->isFromDB) {
                $result = $this->update();
            } else {
                $result = $this->insert();
            }

            if($result === true && (TApplication::isProd() || TApplication::isTest())) {
                $commit = TConnector::commit();
                $result = $commit || $result;
            } else {
                $rollback = TConnector::rollback();
                $result = $rollback || $result;
            }
        }
        
        $this->isFromDB = true;
        $this->isDirty = false;
        
        return $result;
    }
    
    
}
