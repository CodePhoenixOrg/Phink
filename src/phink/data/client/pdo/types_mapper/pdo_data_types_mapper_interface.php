<?php

namespace Phink\Data\CLient\PDO\Mapper;

interface IPdoDataTypesMapper
{
    public function setTypes();

    public function getInfo($index);
    
    public function typeNumToName($type);
    
    public function typeNameToPhp($type);
    
    public function typeNumToPhp($type);
}
