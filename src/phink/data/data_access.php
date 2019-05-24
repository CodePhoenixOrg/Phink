<?php
/*
 * Copyright (C) 2016 David Blanchard
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
 
 // on ouvre la base sbx
namespace Phink\Data;

//require_once 'phink/data/client/pdo/pdo_command.php';

use Phink\Data\Client\PDO\TPdoConfiguration;
use Phink\Data\Client\PDO\TPdoConnection;
use Phink\Data\TServerType;

class TDataAccess
{

    public static function getCryptoDB()
    {

        $databaseName = \Phink\Utils\TFileUtils::filePath(SRC_ROOT . 'data/crypto.db');

        $sqlConfig = new TPdoConfiguration(TServerType::SQLITE, $databaseName);
        $connection = new TPdoConnection($sqlConfig);
        
        $isFound = (file_exists($databaseName));
        $connection->open();
        
        if(!$isFound) {
                    
            $connection->exec("CREATE TABLE crypto (id integer primary key autoincrement, token text, userId text, userName text, outdated integer);");
            $connection->exec("CREATE INDEX crypto_id ON crypto (id);");
            $connection->exec("CREATE UNIQUE INDEX covering_idx ON crypto (token, userId, userName, outdated);");
        }

        return $connection;
    }
}
?>
