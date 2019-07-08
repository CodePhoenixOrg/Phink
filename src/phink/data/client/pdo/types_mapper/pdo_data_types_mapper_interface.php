<?php

namespace Phink\Data\CLient\PDO\Mapper;

interface IPdoDataTypesMapper
{
    public function setTypes() : void;

    public function getInfo(int $index) : ?object;
    
    public function typeNumToName(int $type) : string;
    
    public function typeNameToPhp(string $type) : string;
    
    public function typeNumToPhp(int $type) : string;
}
