<?php
// on ouvre la base sbx
namespace Phoenix\Data;

//require_once 'phoenix/data/client/pdo/pdo_command.php';

use Phoenix\Data\Client\PDO\TPdoConfiguration;
use Phoenix\Data\Client\PDO\TPdoConnection;
use Phoenix\Data\Client\PDO\TPdoCommand;
use Phoenix\Data\TServerType;

class TDataAccess
{

    private static $connection = null;
    
    public static function getCryptoDB()
    {

        $databaseName = \Phoenix\Utils\TFileUtils::filePath(DOCUMENT_ROOT . 'data/crypto.db');

        $sqlConfig = new TPdoConfiguration(TServerType::SQLITE, $databaseName);
        self::$connection = new TPdoConnection($sqlConfig);
        
        $isFound = (file_exists($databaseName));
        self::$connection->open();
        
        if(!$isFound) {
            $command = new TPdoCommand(self::$connection);
                    
            $command->exec("CREATE TABLE crypto (id integer primary key autoincrement, token text, userId text, userName text, outdated integer);");
            $command->exec("CREATE INDEX crypto_id ON crypto (id);");
            $command->exec("CREATE UNIQUE INDEX covering_idx ON crypto (token, userId, userName, outdated);");
        }

        return self::$connection;
    }
}
?>
