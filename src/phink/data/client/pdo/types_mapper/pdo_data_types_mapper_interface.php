<?php

namespace Phink\Data\CLient\PDO\Mapper;

interface IPdoDataTypesMapper
{
    public function types();

    public function getInfo($index);
    
    public function typeNumToName($type);
    
    public function typeNameToPhp($type);
    
    public function typeNumToPhp($type);
}
