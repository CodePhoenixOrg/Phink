<?php

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
