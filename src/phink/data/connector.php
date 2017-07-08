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
 
 namespace Phink\Data;

use Phink\Log\TLog;

interface IStaticConnector {
    static function connect();
    static function isAlive();
    static function isConnected();
    static function query($sql);
    static function queryLog($sql, $filename, $lineNumber);
    static function formatLimitedQyery($sql, $offset, $count);
    static function fetchArray($resource);
    static function nextResult($resource);
    static function fetchObject($resource);
    static function numRows($resource);
    static function numFields($resource);
    static function fieldName($resource, $offset);
    static function fieldLen($resource, $offset);
    static function fieldType($resource, $offset);
    static function close();
    static function kill();
    static function freeResult($resource);
    static function error();
    static function useTransactions($set);
    static function beginTransaction();
    static function getTransactionLevel();
    static function commit();
    static function rollback();
    static function identity();
    static function getRecordset($sql);
}

interface IConnector {
     function connect();
     function query($sql);
     function queryLog($sql, $filename, $lineNumber);
     function formatLimitedQyery($sql, $offset, $count);
     function fetchArray($resource);
     function nextResult($resource);
     function fetchObject($resource);
     function numRows($resource);
     function numFields($resource);
     function fieldName($resource, $offset);
     function fieldLen($resource, $offset);
     function fieldType($resource, $offset);
     function close();
     function freeResult($resource);
     function error();
     function useTransactions($set);
     function beginTransaction();
     function getTransactionLevel();     
     function commit();
     function rollback();
     function identity();
     function getRecordset($sql);
     
}

class TConnector implements IStaticConnector
{
    
    private static $_isAlive = false;
    private static $_isConnected = false;
    private static $_instance = NULL;

    public static function getInstance()
    {
        if(self::$_instance == NULL) {
            throw new Exception("Connexion non définie.");
        }
        
        return self::$_instance;
    }

    public static function setConnector(IConnector $connector)
    {
        self::$_instance = $connector;
    }
    public static function connect()
    {
        TConsoleApplication::writeLine('Connexion par défaut');
        self::$_isAlive = self::getInstance()->connect();
        return self::$_isAlive;
    }
    public static function isConnected()
    {
        return self::$_isConnected;
    }
    public static function isAlive()
    {
        return self::$_isAlive;
    }
    public static function query($sql)
    {
        return self::getInstance()->query($sql);
    }
    public static function queryLog($sql, $filename, $lineNumber)
    {
        return self::getInstance()->queryLog($sql, $filename, $lineNumber);
    }
    public static function formatLimitedQyery($sql, $offset, $count)
    {
        return self::getInstance()->formatLimitedQyery($sql, $offset, $count);
    }
    public static function fetchArray($resource)
    {
        return self::getInstance()->fetchArray($resource);
    }
    public static function nextResult($resource)
    {
        return self::getInstance()->nextResult($resource);
    }
    public static function fetchObject($resource)
    {
        return self::getInstance()->fetchObject($resource);
    }
    public static function numRows($resource)
    {
        return self::getInstance()->numRows($resource);
    }
    public static function numFields($resource)
    {
        return self::getInstance()->numFields($resource);
    }
    public static function fieldName($resource, $offset)
    {
        return self::getInstance()->fieldName($resource, $offset);
    }
    public static function fieldLen($resource, $offset)
    {
        return self::getInstance()->fieldLen($resource, $offset);
    }
    public static function fieldType($resource, $offset)
    {
        return self::getInstance()->fieldType($resource, $offset);
    }
    public static function close()
    {
        TConsoleApplication::writeLine('Ferme la connexion');
        self::$_isConnected = !self::getInstance()->close();
        return self::$_isConnected;
    }
    public static function kill()
    {
        TConsoleApplication::writeLine('Ferme la connexion');
        self::$_isConnected = !self::getInstance()->close();
        TConsoleApplication::writeLine("Tue l'instance");
        unset(self::$_instance);
        self::$_instance = NULL;
        self::$_isAlive = false;
    }
    public static function freeResult($resource)
    {
        return self::getInstance()->freeResult($resource);
    }
    public static function error()
    {
        return self::getInstance()->error();
    }
    public static function useTransactions($set = true)
    {
        if($set) {
            TConsoleApplication::writeLine('Autorise les transactions');
        } else {
            TConsoleApplication::writeLine("N'autorise pas les transactions");
        }
        return self::getInstance()->useTransactions($set);
    }
    public static function beginTransaction()
    {
        if($set) TConsoleApplication::writeLine('Commence une transaction');
        return self::getInstance()->beginTransaction();
    }
    public static function getTransactionLevel()
    {
        return self::getInstance()->getTransactionLevel();
    }
    public static function commit()
    {
        if($set) TConsoleApplication::writeLine('Valide la transaction');
        return self::getInstance()->commit($set);
    }
    public static function rollback()
    {
        if($set) TConsoleApplication::writeLine('Invalide la transaction');
        return self::getInstance()->rollback($set);
    }
    public static function identity()
    {
        return self::getInstance()->identity();
    }
    public static function getRecordset($sql)
    {
        return self::getInstance()->getRecordset($sql);
    }
    
}

//abstract class TPDOSqlSrvDbConnector implements IConnector
//{
//    
//    private $_connection = null;
//    
//    public function connect($serverName = '192.168.1.3', $databaseName = 'Winelite', $user = 'sa', $password = 'unjustice4all')
//    {
//        $result = false;
//        
//        $_connectionInfo = array( "Database"=>"Winelite", 'ReturnDatesAsStrings'=> true, "UID"=>"sa", "PWD"=>"unjustice4all" );
// 
//        $result = new PDO( "sqlsrv:Server=$serverName; Database=$database", $user, $passwd, array(PDO::SQLSRV_ATTR_DIRECT_QUERY => true, PDO::SQLSRV_ATTR_ENCODING => PDO::SQLSRV_ENCODING_SYSTEM));
//
//        if( $result === false ) {
//                die("ECHEC DE CONNEXION A LA BASE AVEC LE MESSAGE : " . print_r( sqlsrv_errors(), true));      
//        }
//        
//        $this->_connection = $result;
//        
//        return $result;
//    }
//    
//    public function query($sql)
//    {
//        $result = false;
//        try {
//            $result = $this->_connection->query($sql); 
////            if(!$result) $result = -1;
//        } catch (Exception $e) {
//            TLog::debug($e->getMessage());
//            
//        }
//
//        return $result;
//    }
//    
//    public function queryLog($sql, $fileName, $lineNumber)
//    {
//        $result = false;
//        try {
//            TLog::debug($fileName . ':' . $lineNumber . ':SQL=' . $sql);
//            //, PDO::SQLSRV_ATTR_CURSOR_SCROLL_TYPE => PDO::SQLSRV_CURSOR_STATIC
//            $stmt = $this->_connection->query($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL)); 
//            $stmt->query(); 
//            $result = $stmt;
////            if(!$result) $result = -1;
//        } catch (Exception $e) {
//            TLog::debug($e->getMessage());
//            
//        }
//
//        return $result;
//    }
//    
//    $resultSet = array();
//    
//    public function fetchArray($resource)
//    {
//        $result = array();
//        try {
//            //$result =(array) $resource->fetch( PDO::FETCH_BOTH, PDO::FETCH_ORI_NEXT ) ; 
//            if(count(self::$resultSet) == 0) {
//                self::$resultSet = (array) $resource->fetchAll() ; 
//                $resource->closeCursor();
//            }
//            if(isset(self::$resultSet[0])) {
//                $row = self::$resultSet[0];
//                $result = $row;
//                array_shift(self::$resultSet);
//            }
//            
////            if(!$result) $result = -1;
//        } catch (Exception $e) {
//            TLog::debug($e->getMessage());
//        }
//        
//        return $result;
//    }
//
//    public function numRows($resource)
//    {
//        $result = false;
//        try {
//            if($resource) {
//                $result = $resource->rowCount() ;
//            } else {
//                $result = 0;
//            }
//
//        } catch (Exception $e) {
//            TLog::debug($e->getMessage());
//        }
//        
//        return $result;
//    }
//    
//    
//}

class TSqlSrvDbConnector implements IConnector
{
    
    private $_connection = null;
    private $_sqlsrvType = null;
    private $_useTransactions = true; // Par défaut on autorise l'usage des transactions pour les requêtes actions
    private $_transactionLevel = 0;
    
    public function identity()
    {
        return 'sqlsrv';
    }
    
    public function getSqlsrvType($typeId)
    {

        if(!$this->_sqlsrvType) {
            $this->_sqlsrvType[-5] = 'bigint';
            $this->_sqlsrvType[-2] = 'binary';
            $this->_sqlsrvType[-7] = 'bit';
            $this->_sqlsrvType[1] = 'char';
            $this->_sqlsrvType[91] = 'date';
            $this->_sqlsrvType[93] = 'datetime';
            $this->_sqlsrvType[93] = 'datetime2';
            $this->_sqlsrvType[-155] = 'datetimeoffset';
            $this->_sqlsrvType[3] = 'decimal';
            $this->_sqlsrvType[6] = 'float';
            $this->_sqlsrvType[-4] = 'image';
            $this->_sqlsrvType[4] = 'int';
            $this->_sqlsrvType[3] = 'money';
            $this->_sqlsrvType[-8] = 'nchar';
            $this->_sqlsrvType[-10] = 'ntext';
            $this->_sqlsrvType[2] = 'numeric';
            $this->_sqlsrvType[-9] = 'nvarchar';
            $this->_sqlsrvType[7] = 'real';
            $this->_sqlsrvType[93] = 'smalldatetime';
            $this->_sqlsrvType[5] = 'smallint';
            $this->_sqlsrvType[3] = 'Smallmoney';
            $this->_sqlsrvType[-1] = 'text';
            $this->_sqlsrvType[-154] = 'time';
            $this->_sqlsrvType[-2] = 'timestamp';
            $this->_sqlsrvType[-6] = 'tinyint';
            $this->_sqlsrvType[-151] = 'udt';
            $this->_sqlsrvType[-11] = 'uniqueidentifier';
            $this->_sqlsrvType[-3] = 'varbinary';
            $this->_sqlsrvType[12] = 'varchar';
            $this->_sqlsrvType[-152] = 'xml';
        }
        
        return $this->_sqlsrvType[$typeId];

    }        
    
    public function connect($serverName = 'DELPHI', $databaseName = 'Phink', $user = 'sa', $password = '1p2+ar')
    {
        $result = false;
        
        if($this->_connection) {
            $this->close();
        }
        
        $_connectionInfo = array( "Database"=>$databaseName, 'ReturnDatesAsStrings'=> true, "UID"=>$user, "PWD"=>$password );

        $result = sqlsrv_connect( $serverName, $_connectionInfo);
        if( $result === false ) {
            self::error();
        }
        
        $this->_connection = $result;
        
        return $result;
    }
    
    public function query($sql)
    {
        $result = false;

        try {
            $result = sqlsrv_query($this->_connection, $sql, array(), array("Scrollable"=>SQLSRV_CURSOR_STATIC));
        } catch (Exception $ex) {
            $result = false;
            TLog::exception($ex, __FILE__, __LINE__);
        }
                
        return $result;
    }
    
    public function queryLog($sql, $fileName, $lineNumber)
    {
        $result = false;
        
        try {
            TLog::debug($fileName . ':' . $lineNumber . ':SQL=' . $sql);
            $result = sqlsrv_query($this->_connection, $sql, array(), array("Scrollable"=>SQLSRV_CURSOR_STATIC));
            if(!$result) TLog::debug($fileName . ':' . $lineNumber . ':SQL erreur = ' . print_r(sqlsrv_errors(), true));
        } catch (Exception $ex) {
            $result = false;
            TLog::exception($ex, __FILE__, __LINE__);
        }
                
        return $result;
    }
    
    public function formatLimitedQyery($sql, $offset, $count)
    {
        $result = $sql;
        $fields = GQueryManager::getFieldsFromSelectClause($sql);
        $keyField = $fields[0];
        $distinct = '';
        if($keyField == 'distinct') 
        {
            $distinct = ' ' . $keyField;
            $keyField = $fields[1];
        }
//        $limitedSql = strtolower($sql);
        $p = strpos($limitedSql, 'order');
        if($p) {
            $sql = substr($sql, 0, $p - 1);
            
        }
        $sql = substr($sql, 6);

        if($distinct != '') {
            $distinctPos = strpos($sql, 'distinct');
            $sql = substr($sql, 0, $distinctPos) .  substr($sql, $distinctPos + 9);
            
        }
        
        $top = $offset+$count;
        $result = "
            SELECT * FROM (
                SELECT TOP $count 
                * FROM (
                    SELECT $distinct TOP $top
                    $sql
                    ORDER BY $keyField
                ) as myData
                ORDER BY $keyField DESC
            ) as myData2
            ORDER BY $keyField;";

        return $result;                
    }

    public function fetchArray($resource)
    {
        $result = false;
        
        if($resource) {
            $result = sqlsrv_fetch_array($resource);
        }
        
        return $result;
    }
    
    public function nextResult($resource)
    {
        $result = false;
        
        if($resource) {
            $result = sqlsrv_next_result($resource);
        }
        
        return $result;
    }

    public function fetchObject($resource)
    {
        $result = false;
        
        if($resource) {
            $result = sqlsrv_fetch_object($resource);
        }
        
        return $result;
    }    

    public function numRows($resource)
    {
        $result = false;
    
        if($resource) {
            $result = sqlsrv_num_rows($resource);
        }
        
        return $result;
    }
    
    public function numFields($resource)
    {
        $result = false;
        
        if($resource) {
            $result = sqlsrv_num_fields($resource);
        }
        
        return $result;
    }
    
    public function fieldName($resource, $offset)
    {
        $result = false;
        
        if($resource) {
            $data = sqlsrv_field_metadata($resource);
            $result = $data[$offset]['Name'];
        }
        
        return $result;
    }
    
    public function fieldLen($resource, $offset)
    {
        $result = false;
        
        if($resource) {
            $data = sqlsrv_field_metadata($resource);
            $result = $data[$offset]['Size'];
        }
        
        return $result;
    }
    
    public function fieldType($resource, $offset)
    {
        $result = false;
        
        if($resource) {
            $data = sqlsrv_field_metadata($resource);
            $typeId = $data[$offset]['Type'];
            $result = $this->getSqlsrvType($typeId);
        }
        
        return $result;
    }

    public function close()
    {
        $result = false;
        
        if($this->_connection) {
            $result = sqlsrv_close($this->_connection);
        }        
        
        return $result;
    }
    
    public function freeResult($resource)
    {
        $result = false;
        
        if($resource) {
            $result = sqlsrv_free_stmt($resource);
        }
        
        return $result;
    }
    
    public function error()
    {
        $result = false;
        
        if($data = sqlsrv_errors()) {
            $result = $data['SQLSTATE'] . PHP_EOL . $data['code'] . PHP_EOL . $data['message'];
        }
        
        return $result;
    }    
    
    public function useTransactions($set)
    {
        $this->_useTransactions = $set;
        $this->_transactionLevel = 0;
        if(!$this->_useTransactions) {
            if(TApplication::getVerboseMode()) {
                TConsoleApplication::writeLine('Transaction non-autorisée');
            }
        } else {
            if(TApplication::getVerboseMode()) {
                TConsoleApplication::writeLine('Transaction autorisée');
            }
        }
    }

    public function beginTransaction()
    {
        $result = false;
        $this->_transactionLevel++;
        if(TApplication::getVerboseMode()) {
            TConsoleApplication::writeLine('Niveau de transaction : ' . $this->_transactionLevel);
        }
        $setTransaction = ($this->_useTransactions && $this->_transactionLevel == 1);
        
        if($this->_connection && $setTransaction) {
            if(TApplication::getVerboseMode()) {
                TConsoleApplication::writeLine('transaction initiée');
            }
            $result = sqlsrv_begin_transaction($this->_connection);
        } elseif($this->_connection && !$setTransaction) {
            $result = true;
        } 
        
        return $result;
    }
    
    public function getTransactionLevel()
    {
        return $this->_transactionLevel;
    }
    
    public function commit()
    {
        $result = false;
        if(TApplication::getVerboseMode()) {
            TConsoleApplication::writeLine('Niveau de transaction : ' . $this->_transactionLevel);
        }
        $setTransaction =  ($this->_useTransactions && $this->_transactionLevel == 1);
        $this->_transactionLevel--;
        
        if($this->_connection && $setTransaction) {
            $result = sqlsrv_commit($this->_connection);
            if(TApplication::getVerboseMode()) {
                TConsoleApplication::writeLine('transaction validée');
            }
        } elseif($this->_connection && !$setTransaction) {
            $result = true;
        }
        
        return $result;
    }

    public function rollback()
    {
        $result = false;
        if(TApplication::getVerboseMode()) {
            TConsoleApplication::writeLine('Niveau de transaction : ' . $this->_transactionLevel);
        }
        $setTransaction =  ($this->_useTransactions && $this->_transactionLevel == 1);
        $this->_transactionLevel--;
        
        if($this->_connection && $setTransaction) {
            $result = sqlsrv_rollback($this->_connection);
            if(TApplication::getVerboseMode()) {
                TConsoleApplication::writeLine('transaction annulée');
            }
        } elseif($this->_connection && !$setTransaction) {
            $result = true;
        }

        return $result;
    }

    public function getRecordset($sql)
    {
	$recordset = (array) null;
	$names = (array) null;
	$types = (array) null;
	$values = (array) null;

	$result = self::query($sql) or die($sql);
	$nfields = self::numFields($result);
	//$nrows=DbConnector::numRows($result);
	
	for($i = 0; $i < $nfields; $i++) {
		$names[$i]=self::fieldName($result, $i);
	}
	for($i = 0; $i < $nfields; $i++) {
		$types[$i]=self::fieldType($result, $i);
	}
	
	while($rows = self::fetchArray($result)) {
		array_push($values, $rows);
	}
	$recordset = array("names" => $names, "values" => $values, "types" => $types);
        
	return $recordset;
    }
    
}

class TMsSqlDbConnector implements IConnector
{
    
    private $_connection = null;
    private $_useTransactions = true; // Par défaut on autorise l'usage des transactions pour les requêtes actions
    private $_transactionLevel = 0;

    public function identity()
    {
        return 'mssql';
    }
    
    public function connect($serverName = '192.168.1.3', $databaseName = 'Winelite', $user = 'sa', $password = 'unjustice4all')
    {
        $result = false;
        
        if($this->_connection) {
            $this->close();
        }
        
        $result = mssql_pconnect($serverName, $user, $password);
        mssql_select_db($databaseName, $result);
        if( $result === false ) {
                die("ECHEC DE CONNEXION A LA BASE ");      
        }
        
        $this->_connection = $result;
        
        return $result;
    }
    
    public function query($sql)
    {
        $result = false;
    
        try {
            $result = mssql_query($sql, $this->_connection);
        } catch (Exception $ex) {
            $result = false;
            TLog::exception($ex, __FILE__, __LINE__);
        }
                
        return $result;
    }
    
    public function queryLog($sql, $fileName = __FILE__, $lineNumber = __LINE__)
    {
        $result = false;
        
        try {
            TLog::debug('SQL=' . $sql, $fileName, $lineNumber);
            $result = mssql_query($sql, $this->_connection);
            if(!$result) TLog::debug($fileName . ':' . $lineNumber . ':SQL erreur = ' . print_r(mssql_get_last_message(), true));
            
        } catch (Exception $ex) {
            $result = false;
            TLog::exception($ex, __FILE__, __LINE__);
        }

        
        return $result;
    }
    
    public function formatLimitedQyery($sql, $offset, $count)
    {
        $result = $sql;
        $fields = GQueryManager::getFieldsFromSelectClause($sql);
        $keyField = $fields[0];
        $distinct = '';
        if($keyField == 'distinct') 
        {
            $distinct = ' ' . $keyField;
            $keyField = $fields[1];
        }
//        $limitedSql = strtolower($sql);
        $p = strpos($limitedSql, 'order');
        if($p) {
            $sql = substr($sql, 0, $p - 1);
            
        }
        $sql = substr($sql, 6);

        if($distinct != '') {
            $distinctPos = strpos($sql, 'distinct');
            $sql = substr($sql, 0, $distinctPos) .  substr($sql, $distinctPos + 9);
            
        }
        
        $top = $offset+$count;
        $result = "
            SELECT * FROM (
                SELECT TOP $count 
                * FROM (
                    SELECT $distinct TOP $top
                    $sql
                    ORDER BY $keyField
                ) as myData
                ORDER BY $keyField DESC
            ) as myData2
            ORDER BY $keyField;";

        return $result;                
    }
   
    public function fetchArray($resource)
    {
        $result = false;
        
        if($resource) {
            $result = mssql_fetch_array($resource);
        }
        
        return $result;
    }
    
    public function nextResult($resource)
    {
        $result = false;
        
        if($resource) {
            $result = mssql_next_result($resource);
        }
        
        return $result;
    }
    
    public function fetchObject($resource)
    {
        $result = false;
        
        if($resource) {
            $result = mssql_fetch_object($resource);
        }
        
        return $result;
    }    

    public function numRows($resource)
    {
        $result = false;
        
        if($resource) {
            $result = mssql_num_rows($resource);
        }
        
        return $result;
    }
    
    
    public function numFields($resource)
    {
        $result = false;
        
        if($resource) {
            $result = mssql_num_fields($resource);
        }
        
        return $result;
    }
    
    public function fieldName($resource, $offset)
    {
        $result = false;
        
        if($resource) {
            $result = mssql_field_name($resource, $offset);
        }
        
        return $result;
    }
    
    public function fieldLen($resource, $offset)
    {
        $result = false;
        
        if($resource) {
            $result = mssql_field_length($resource, $offset);
        }
        
        return $result;
    }
    
    public function fieldType($resource, $offset)
    {
        $result = false;
        
        if($resource) {
            $result = mssql_field_type($resource, $offset);
        }
        
        return $result;
    }

    public function close()
    {
        $result = false;
        
        if($this->_connection) {
            $result = mssql_close($this->_connection);
        }
        
        return $result;
    }
    
    public function freeResult($resource)
    {
        $result = false;
        
        if($resource) {
            $result = mssql_free_result($resource);
        }
        
        return $result;
    }
    
    public function error($resource = NULL)
    {
        $result = false;
        
        if($resource == NULL) {
            $result = mssql_get_last_message();
        }
        else {
            $result = mssql_get_last_message($resource);
        }
        
        return $result;
    }    
    
    public function useTransactions($set)
    {
        $this->_useTransactions = $set;
        $this->_transactionLevel = 0;
        if(!$this->_useTransactions) {
            TConsoleApplication::writeLine('Transaction non-autorisée');
        } else {
            TConsoleApplication::writeLine('Transaction autorisée');
        }
    }

    public function beginTransaction()
    {
        $result = false;
        $this->_transactionLevel++;
        TConsoleApplication::writeLine('Niveau de transaction : ' . $this->_transactionLevel);
        $setTransaction =  ($this->_useTransactions && $this->_transactionLevel == 1);
        
        if($this->_connection && $setTransaction) {
            TConsoleApplication::writeLine('transaction initiée');
            $result = mssql_query('BEGIN TRAN', $this->_connection);
        } elseif($this->_connection && !$setTransaction) {
            $result = true;
        } 
        
        return $result;
    }
    
    public function getTransactionLevel()
    {
        return $this->_transactionLevel;
    }
    
    public function commit()
    {
        $result = false;
        TConsoleApplication::writeLine('Niveau de transaction : ' . $this->_transactionLevel);
        $setTransaction =  ($this->_useTransactions && $this->_transactionLevel == 1);
        $this->_transactionLevel--;
        
        if($this->_connection && $setTransaction) {
            $result = mssql_query('COMMIT', $this->_connection);
        } elseif($this->_connection && !$setTransaction) {
            $result = true;
        }
        
        return $result;
    }

    public function rollback()
    {
        $result = false;
        TConsoleApplication::writeLine('Niveau de transaction : ' . $this->_transactionLevel);
        $setTransaction =  ($this->_useTransactions && $this->_transactionLevel == 1);
        $this->_transactionLevel--;
        
        if($this->_connection && $setTransaction) {
            $result = mssql_query('ROLLBACK', $this->_connection);
        } elseif($this->_connection && !$setTransaction) {
            $result = true;
        }

        return $result;
    }
    
    public function getRecordset($sql)
    {
	$recordset = (array) null;
	$names = (array) null;
	$types = (array) null;
	$values = (array) null;

	$result = self::query($sql) or die($sql);
	$nfields = self::numFields($result);
	//$nrows=DbConnector::numRows($result);
	
	for($i = 0; $i < $nfields; $i++) {
		$names[$i]=self::fieldName($result, $i);
	}
	for($i = 0; $i < $nfields; $i++) {
		$types[$i]=self::fieldType($result, $i);
	}
	
	$i=0;
	while(($rows = self::fetchArray($result)) && ($i < 256)) {
		$values[$i] = array_unique($rows);
		$i++;
	}
	$recordset = array("names" => $names, "values" => $values, "types" => $types);
        
	return $recordset;
    }

}

// MYSQL

class TMySqlDbConnector implements IConnector
{
    
    private $_connection = null;
    private $_useTransactions = true; // Par défaut on autorise l'usage des transactions pour les requêtes actions
    private $_transactionLevel = 0;


    public function identity()
    {
        return 'mysql';
    }

    public function connect($serverName = '192.168.1.3', $databaseName = 'Winelite', $user = 'sa', $password = 'unjustice4all')
    {
        $result = false;
        
        if($this->_connection) {
            $this->close();
        }

        $result = mysqli_connect($serverName, $user, $password, $databaseName);
        //mysqli_select_db($databaseName, $result);
        if($result === false) {
            die("ECHEC DE CONNEXION A LA BASE ");      
        }
        
        $this->_connection = $result;
        
        return $result;
    }
    
    public function query($sql)
    {
        $result = false;
        
        $result = mysqli_query($this->_connection, $sql);
                
        return $result;
    }
    
    public function queryLog($sql, $fileName = __FILE__, $lineNumber = __LINE__)
    {
        $result = false;
        
        TLog::debug('SQL=' . $sql, $fileName, $lineNumber);
        $result = mysqli_query($this->_connection, $sql);
        
        return $result;
    }
    
    public function formatLimitedQyery($sql, $offset, $count)
    {
        $result = $sql;
        
        return $result;
                
    }

    public function fetchArray($resource)
    {
        $result = false;
        
        if($resource) {
            $result = mysqli_fetch_array($resource);
        }
        
        return $result;
    }
    
    public function nextResult($resource)
    {
        $result = false;
        
        if($resource) {
            $result = mysqi_next_result($resource);
        }
        
        return $result;
    }
    
    public function fetchObject($resource)
    {
        $result = false;
        
        if($resource) {
            $result = mysqli_fetch_object($resource);
        }
        
        return $result;
    }    

    public function numRows($resource)
    {
        $result = false;
        
        if($resource) {
            $result = mysqli_num_rows($resource);
        }
        
        return $result;
    }
    
    public function numFields($resource)
    {
        $result = false;
        
        if($resource) {
            $result = mysqli_num_fields($resource);
        }
        
        return $result;
    }
    
    public function fieldName($resource, $offset)
    {
        $result = false;
        
        if($resource) {
            $result = mysqli_field_name($resource, $offset);
        }
        
        return $result;
    }
    
    public function fieldLen($resource, $offset)
    {
        $result = false;
        
        if($resource) {
            $result = mysqli_field_len($resource, $offset);
        }
        
        return $result;
    }
    
    public function fieldType($resource, $offset)
    {
        $result = false;
        
        if($resource) {
            $result = mysqli_field_type($resource, $offset);
        }
        
        return $result;
    }

    public function close()
    {
        $result = false;

        if($this->_connection) {
            $result = mysqli_close($this->_connection);
        }
        
        return $result;
    }
    
    public function freeResult($resource)
    {
        $result = false;
        
        if($resource) {
            $result = mysqli_free_result($resource);
        }
        
        return $result;
    }
    
    public function error($resource = NULL)
    {
        $result = false;
        
        if($resource == NULL) {
            $result = mysqli_error();
        }
        else {
            $result = mysqli_error($resource);
        }
        
        return $result;
    }
    
    public function useTransactions($set)
    {
        $this->_useTransactions = $set;
        $this->_transactionLevel = 0;
        if(!$this->_useTransactions) {
            TConsoleApplication::writeLine('Transaction non-autorisée');
        } else {
            TConsoleApplication::writeLine('Transaction autorisée');
        }
    }

    public function beginTransaction()
    {
        $result = false;
        $this->_transactionLevel++;
        TConsoleApplication::writeLine('Niveau de transaction : ' . $this->_transactionLevel);
        $setTransaction =  ($this->_useTransactions && $this->_transactionLevel == 1);
        
        if($this->_connection && $setTransaction) {
            TConsoleApplication::writeLine('transaction initiée');
            $result = mysqli_query('START TRANSACTION', $this->_connection);
        } elseif($this->_connection && !$setTransaction) {
            $result = true;
        } 
        
        return $result;
    }
    
    public function getTransactionLevel()
    {
        return $this->_transactionLevel;
    }
    
    public function commit()
    {
        $result = false;
        TConsoleApplication::writeLine('Niveau de transaction : ' . $this->_transactionLevel);
        $setTransaction =  ($this->_useTransactions && $this->_transactionLevel == 1);
        $this->_transactionLevel--;
        
        if($this->_connection && $setTransaction) {
            $result = mssql_query('COMMIT', $this->_connection);
        } elseif($this->_connection && !$setTransaction) {
            $result = true;
        }
        
        return $result;
    }

    public function rollback()
    {
        $result = false;
        TConsoleApplication::writeLine('Niveau de transaction : ' . $this->_transactionLevel);
        $setTransaction =  ($this->_useTransactions && $this->_transactionLevel == 1);
        $this->_transactionLevel--;
        
        if($this->_connection && $setTransaction) {
            $result = mssql_query('ROLLBACK', $this->_connection);
        } elseif($this->_connection && !$setTransaction) {
            $result = true;
        }

        return $result;
    }

    public function getRecordset($sql)
    {
	$recordset = (array) null;
	$names = (array) null;
	$types = (array) null;
	$values = (array) null;

	$result = self::query($sql) or die($sql);
	$nfields = self::numFields($result);
	//$nrows=DbConnector::numRows($result);
	
	for($i = 0; $i < $nfields; $i++) {
		$names[$i]=self::fieldName($result, $i);
	}
	for($i = 0; $i < $nfields; $i++) {
		$types[$i]=self::fieldType($result, $i);
	}
	
	$i=0;
	while(($rows = self::fetchArray($result)) && ($i < 256)) {
		$values[$i] = array_unique($rows);
		$i++;
	}
	$recordset = array("names" => $names, "values" => $values, "types" => $types);
        
	return $recordset;
    }
    
}

class TQueryManager
{
    
    public static function getFieldsFromSelectClause($sql)
    {
        $select = "select";
        $from = "from";

        $result = array();

        $sql = str_replace("\r", '', $sql);
        $sql = str_replace("\n", '', $sql);
        $sql = str_replace("\t", ' ', $sql);
        
//        $sql = strtolower($sql);
        $l = strlen($select)+1;
        $p = strpos($sql, $from);
        $fields = substr($sql, $l, $p-$l);

        $fields = explode(",", $fields);
        $i=0;
        foreach($fields as $field) {
            $afields = explode(" ", trim($field));
            if(count($afields > 3)) {
                $afields = array_values(array_filter($afields));
            }
           
            if($afields[0] == 'distinct') {
                array_push($result, $afields[0]);
                array_push($result, $afields[1]);
            } else {
                array_push($result, $afields[0]);
            }
            break;
        }

        return $result;
    }
}
