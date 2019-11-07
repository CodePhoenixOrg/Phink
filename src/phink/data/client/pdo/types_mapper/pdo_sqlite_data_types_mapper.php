<?php

namespace Phink\Data\CLient\PDO\Mapper;

use Phink\Data\CLient\PDO\Mapper\TPdoCustomDataTypesMapper;

class TPdoSQLiteDataTypesMapper extends TPdoCustomDataTypesMapper
{
    public function getInfo($index) : ?object
    {
        if ($this->result === null) {
            try {
                $connection = new \SQLite3(
                    $this->config->getDatabaseName()
                );

                $this->result = $connection->query($this->sql);
            } catch (\Exception $ex) {
                return null;
            }
        }

        $name = $this->result->columnName($index);
        $type = $this->result->columnType($index);
        $len = 32768;

        $this->info = (object) ['name' => $name, 'type' => $type, 'length' => $len];

        return $this->info;
    }

    public function setTypes() : void
    {
        $this->native_types = (array) null;
        $this->native2php_assoc = (array) null;
        $this->native2php_num = (array) null;

        $this->native_types[1] = "INTEGER";
        $this->native_types[2] = "TEXT";
        $this->native_types[3] = "BLOB";
        $this->native_types[4] = "REAL";
        $this->native_types[5] = "NUMERIC";
        
        $this->native2php_assoc["INTEGER"] = "int";
        $this->native2php_assoc["TEXT"] = "string";
        $this->native2php_assoc["BLOB"] = "blob";
        $this->native2php_assoc["REAL"] = "float";
        $this->native2php_assoc["NUMERIC"] = "float";
        
        $this->native2php_num[1] = "int";
        $this->native2php_num[2] = "string";
        $this->native2php_num[3] = "blob";
        $this->native2php_num[4] = "float";
        $this->native2php_num[5] = "float";
    }
}