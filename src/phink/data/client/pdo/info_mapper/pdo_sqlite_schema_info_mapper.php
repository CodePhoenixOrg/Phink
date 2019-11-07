<?php

namespace Phink\Data\CLient\PDO\Mapper;

use Phink\Data\CLient\PDO\Mapper\TPdoCustomDataTypesMapper;

class TPdoSQLiteSchemaInfoMapper extends TPdoCustomSchemaInfoMapper
{
    public function getShowTablesQuery() : string
    {
        $sql = <<<SQL
        SELECT 
            name
        FROM 
            sqlite_master 
        WHERE 
            type ='table' AND 
            name NOT LIKE 'sqlite_%';
        SQL;

        return $sql;
    }

    public function getShowFieldsQuery(string $table) : string
    {
        $sql = <<<SQL
            SELECT name FROM PRAGMA_TABLE_INFO('{$table}');
        SQL;

        return $sql;
    }
}
