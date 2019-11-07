<?php

namespace Phink\Data\CLient\PDO\Mapper;

use Phink\Data\CLient\PDO\Mapper\IPdoSchemaInfoMapper;
use Phink\Data\Client\PDO\TPdoConfiguration;


abstract class TPdoCustomSchemaInfoMapper implements IPdoSchemaInfoMapper
{
    protected $config = null;
    protected $driver = '';

    public function __construct(TPdoConfiguration $config)
    {
        $this->config = $config;
    }


    public function getShowTablesQuery() : string
    {
    }

    public function getShowFieldsQuery(string $table) : string
    {
    }


}
