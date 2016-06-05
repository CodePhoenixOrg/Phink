<?php
// on ouvre la base sbx
namespace Phink\Data;

//require_once 'phink/data/client/pdo/pdo_command.php';

use Phink\Data\Client\PDO\TPdoConfiguration;
use Phink\Data\Client\PDO\TPdoConnection;
use Phink\Data\Client\PDO\TPdoCommand;
use Phink\Data\TServerType;

class TDataAccess
{

    public static function getCryptoDB()
    {

        $databaseName = \Phink\Utils\TFileUtils::filePath(DOCUMENT_ROOT . 'data/crypto.db');

        $sqlConfig = new TPdoConfiguration(TServerType::SQLITE, $databaseName);
        $connection = new TPdoConnection($sqlConfig);
        
        $isFound = (file_exists($databaseName));
        $connection->open();
        
        if(!$isFound) {
            $command = new TPdoCommand($connection);
                    
            $command->exec("CREATE TABLE crypto (id integer primary key autoincrement, token text, userId text, userName text, outdated integer);");
            $command->exec("CREATE INDEX crypto_id ON crypto (id);");
            $command->exec("CREATE UNIQUE INDEX covering_idx ON crypto (token, userId, userName, outdated);");
        }

        return $connection;
    }
}
?>
