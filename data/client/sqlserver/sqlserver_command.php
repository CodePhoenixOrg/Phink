<?php

namespace Phoenix\Data\Client\SqlServer;

//require_once 'phoenix/core/object.php';
//require_once 'phoenix/data/command.php';
//require_once 'phoenix/data/crud_queries.php';
//require_once 'phoenix/utils/sql_utils.php';
//require_once 'sqlserver_data_reader.php';
//require_once 'sqlserver_connection.php';

use Phoenix\Core\TObject;
use Phoenix\Data\ICommand;
use Phoenix\Data\TCrudQueries;
use Phoenix\Data\Client\SqlServer\TSqlServerConnection;
use Phoenix\Data\Client\SqlServer\TSqlServerDataReader;
use Phoenix\Utils\TSqlUtils;
/**
 * Description of amysqlcommand
 *
 * @author david
 */
class TSqlServerCommand extends TObject implements ICommand
{
    //put your code here
    private $_fieldCount;
    private $_rowCount;
    private $_result;
    private $_reader;
    private $_queries;
    private $_activeConnection;

    public function __construct(TSqlServerConnection $activeConnection)
    {
        $this->_activeConnection = $activeConnection;
        $this->_queries = new TCrudQueries();
    }

    public function executeReader()
    {
        $this->_result = mssql_query($this->_queries->getSelect(), $this->_activeConnection->getState());
        $this->_reader = new TSqlServerDataReader($this->_result);
        //$this->setFieldCount();
        //$this->setRowCount();

        return $this->_reader;
    }

    public function executeLimitedReader()
    {

        return $this->executeReaderPage(APage::pageNumber(), APage::pageCount());
    }

    public function executeReaderPage($page, $count)
    {
        $stmt = mssql_init('a_sp_SelectQueryPage', $this->_activeConnection->getState());
        mssql_bind($stmt, '@query', $this->_queries->getSelect(), SQLTEXT);
        mssql_bind($stmt, '@fieldId', TSqlUtils::firstFieldFromSelectClause($this->_queries->getSelect()), SQLVARCHAR, false, false, 255);
        mssql_bind($stmt, '@page', $page, SQLINT4);
        mssql_bind($stmt, '@count', $count, SQLINT4);

        $this->_result = mssql_execute($stmt);
        $this->_reader = new TSqlServerDataReader($this->_result);

        mssql_free_statement($stmt);
    }

    public function executeNonQuery()
    {

    }

    public function getQueries()
    {
        return $this->_queries;
    }

    public function getActiveConnection()
    {
        return $this->_activeConnection;
    }

    public function getReader()
    {
        return $this->_reader;
    }
    
    public function getFieldCount()
    {
        if(!isset($this->_fieldCount)) {
            $this->_fieldCount = mssql_num_fields($this->_result);
        }
        return $this->_fieldCount;
    }

    public function getRowCount()
    {
//        if(!isset($this->_rowCount)) {
//            $this->_rowCount = mssql_num_rows($this->_result);
//        }
//        return $this->_rowCount;
        return -1;

    }

    public function execute($stmt)
    {
        $this->_result = mssql_execute($stmt);
    }

    public function fieldName($i)
    {
        return mssql_field_name($this->_result, $i);
    }

    public function fieldType($i)
    {
        return mssql_field_type($this->_result, $i);
    }

    public function fieldLen($i)
    {
        return mssql_field_length($this->_result, $i);
    }

    public function __destruct()
    {
        mssql_free_result($this->_result);
    }
}

?>
