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

    public function init(): void {}
    
    public function getDbConnector(): IConnector
    {
        return $this->dbConnector;
    }
    public function setDbConnector(IConnector $value): void
    {
        $this->dbConnector = $value;
    }
    
    public function select() {}
    
    public function insert() {}
    
    public function update() {}

    public function delete() {}

    public function getInsertId(): int
    {
        return -1;
    }

    public function isNew(): bool
    {
        return $this->isNew;
    }

    public function isDirty(): bool
    {
        return $this->isDirty;
    }
    
    public function isFromDB(): bool
    {
        return $this->isFromDB;
    }

    public function getIndex(): int
    {
        return $this->index;
    }
    
    public function setIndex($value): void
    {
        $this->index = $value;
    }
            
    public function setTransaction($set = true): void
    {
        $this->setTransaction = $set;
    }
    
    public function setDirty(): void
    {
        $this->isDirty = true;
        $this->isNew = false;
    }
    
    public function setFromDB($set = true): void
    {
        $this->isFromDB = $set;
        if($set) {
            $this->isNew = !$set;
        }
    }

    public function save(): bool
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
