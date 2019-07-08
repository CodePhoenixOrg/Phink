<?php

namespace Phink\Data\CLient\PDO\Mapper;

use Phink\Data\CLient\PDO\Mapper\IPdoDataTypesMapper;
use Phink\Data\Client\PDO\TPdoConfiguration;


abstract class TPdoCustomDataTypesMapper implements IPdoDataTypesMapper
{
    protected $statement;
    protected $values;
    protected $fieldCount;
    protected $rowCount;
    protected $meta = [];
    protected $colNames = [];
    protected $config = null;
    protected $sql = '';
    protected $result = null;
    protected $driver = '';
    protected $native_types = [];
    protected $native2php_assoc = [];
    protected $native2php_num = [];
    protected $typesMapper = null;
    protected $cs = null;
    protected $info = null;

    public function __construct(TPdoConfiguration $config, string $sql)
    {
        $this->config = $config;
        $this->sql = $sql;
        $this->setTypes();
    }

    public function setTypes() : void {}

    public function getInfo(int $index) : ?object {}

    public function typeNumToName(int $type) : string
    {
        return $this->native_types[$type];
    }

    public function typeNameToPhp(string $type) : string
    {
        return $this->native2php_assoc[$type];
    }

    public function typeNumToPhp(int $type) : string
    {
        return $this->native2php_num[$type];
    }

}
