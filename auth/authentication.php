<?php // Authentification
namespace Phoenix\Auth;

//require_once 'phoenix/core/response.php';
//require_once 'phoenix/crypto/crypto.php';
//require_once 'phoenix/data/data_access.php';
//require_once 'phoenix/data/client/pdo/pdo_command.php';

use Phoenix\Web\TResponse;
use Phoenix\Crypto\TCrypto;
use Phoenix\Data\TDataAccess;
use Phoenix\Data\Client\PDO\TPdoCommand;
use Phoenix\Log\TLog;

class TAuthentication
{

    public static function getPermissionByToken ($token)
    {
        
        $result = false;
        
        if($token != '') {

            $token = self::renewToken($token);
            if(is_string($token)) {
                $result = $token;
            }
        }
        
        return $result;
    }    

    public static function setUserToken($userId, $login)
    {
        $result = false;
        
        $connection = TDataAccess::getCryptoDB();
        $command = new TPdoCommand($connection);
        $token = TCrypto::generateToken('');
        $stmt = $command->query(
            "INSERT INTO crypto (token, userId, userName, outdated) VALUES(:token, :userId, :login, 0);"
            ,['token' => $token, 'userId' => $userId, 'login' => $login]
        );

        return ($token || $stmt->fetch()) ? $token : $result;
    }    
    
    public function updateToken($token)
    {
        $result = false;
        
        $connection = TDataAccess::getCryptoDB();
        $command = new TPdoCommand($connection);
        $stmt = $command->query("select * from crypto where token=:token and outdated=0;", ['token' => $token]);

        if ($stmt->fetch()) {
            $stmt = $command->query("UPDATE crypto SET outdated=1 WHERE token=:token;", ['token' => $token]);
            if ($stmt->fetch()) {
                $result = $command->getRowCount();
            }
        }
        
        return $result;
    }    
    
    public static function renewToken($token = '')
    {
        $result = false;

        if(strlen($token) > 0 && substr($token, 0, 1) == '!') {
            $result = $token;
            return $result;
        }
        $connection = TDataAccess::getCryptoDB();
        $command = new TPdoCommand($connection);
        $stmt = $command->query("select * from crypto where token =:token and outdated=0;", ['token' => $token]);
        if ($row = $stmt->fetchAssoc()) {
            
            $userId = $row["userId"];
            $login = $row["userName"];
            
            $stmt = $command->query("UPDATE crypto SET outdated=1 WHERE token =:token;", ['token' => $token]);
            
            $token = TCrypto::generateToken('');
            $command->query(
                "INSERT INTO crypto (token, userId, userName, outdated) VALUES(:token, :userId, :login, 0);"
                ,['token' => $token, 'userId' => $userId, 'login' => $login]
            );
           
            $result = $token;
        }

        return $result;
    }    
    
}

