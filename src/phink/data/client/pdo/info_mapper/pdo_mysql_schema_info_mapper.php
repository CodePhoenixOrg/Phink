<?php

namespace Phink\Data\CLient\PDO\Mapper;

use Phink\Data\CLient\PDO\Mapper\TPdoCustomDataTypesMapper;

class TPdoMySQLSchemaInfoMapper extends TPdoCustomSchemaInfoMapper
{
    public function getShowTablesQuery() : string
    {
        $sql = <<<SQL
        show tables from {$this->config->getDatabaseName()};
        SQL;

        return $sql;
    }

    public function getShowFieldsQuery(string $table) : string
    {
        $sql = <<<SQL
        show fields from $table;
        SQL;

        return $sql;
    }
}
