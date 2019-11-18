<?php

namespace Phink\Data\CLient\PDO\SchemaInfo;

interface IPdoSchemaInfo
{
    public function setTypes() : void;

    public function getInfo(int $index) : ?object;
    
    public function typeNumToName(int $type) : string;
    
    public function typeNameToPhp(string $type) : string;
    
    public function typeNumToPhp(int $type) : string;
    
    public function getShowTablesQuery() : string;

    public function getShowFieldsQuery(?string $table) : string;
    
    public function getFieldCount() : int;
}
