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

    public function setTypes() {}

    public function getInfo($index) {}

    public function typeNumToName($type)
    {
        return $this->native_types[$type];
    }

    public function typeNameToPhp($type)
    {
        return $this->native2php_assoc[$type];
    }

    public function typeNumToPhp($type)
    {
        return $this->native2php_num[$type];
    }

}
