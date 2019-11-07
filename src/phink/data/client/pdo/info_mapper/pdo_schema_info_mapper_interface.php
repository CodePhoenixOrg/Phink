<?php

namespace Phink\Data\CLient\PDO\Mapper;

interface IPdoSchemaInfoMapper
{
    public function getShowTablesQuery() : string;

    public function getShowFieldsQuery(string $table) : string;

}
