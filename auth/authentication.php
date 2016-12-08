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

namespace Phink\Auth;

use Phink\Crypto\TCrypto;
use Phink\Data\TDataAccess;
use Phink\Data\Client\PDO\TPdoCommand;

class TAuthentication extends \TStaticObject
{

    protected $userId = null;
    protected $userName = null;

    public function getUserId()
    {
        if (!isset($this->userId) || empty($this->userId)) {
            if (isset($_SESSION['userId'])) {
                $this->userId = $_SESSION['userId'];
            } else {
                $this->setUserId('#!user' . uniqid() . '#');
            }
        }
        return $this->userId;
    }

    public function setUserId($value)
    {
        $_SESSION['userId'] = $value;
        $this->userId = $value;
    }

    public function getUserName()
    {
        if (!isset($this->userName) || empty($this->userName)) {
            if (isset($_SESSION['userName'])) {
                $this->userName = $_SESSION['userName'];
            } else {
                $this->setUserName('#!user' . uniqid() . '#');
            }
        }
        return $this->userName;
    }
    
    public function setUserName($value)
    {
        $_SESSION['userName'] = $value;
        $this->userName = $value;
    }

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
    
    public function renewToken($token = '')
    {
        $result = false;

        self::$logger->debug(__METHOD__ . '::TOKEN::' . $token);
        
        if(strlen($token) > 0 && substr($token, 0, 1) == '!') {
            $result = $token;
            return $result;
        }
        
        $userId = $this->getUserId();
        $login = $this->getUserName();
        
        $connection = TDataAccess::getCryptoDB();
        $command = new TPdoCommand($connection);
        $stmt = $command->query("select * from crypto where token =:token and outdated=0;", ['token' => $token]);
        if ($row = $stmt->fetchAssoc()) {
            
            $userId = $row["userId"];
            $login = $row["userName"];
            
            $stmt = $command->query("UPDATE crypto SET outdated=1 WHERE token =:token;", ['token' => $token]);
            
        }
        
        $token = TCrypto::generateToken('');
        $command->query(
            "INSERT INTO crypto (token, userId, userName, outdated) VALUES(:token, :userId, :login, 0);"
            ,['token' => $token, 'userId' => $userId, 'login' => $login]
        );
           
        $result = $token;

        return $result;
    }    
    
}