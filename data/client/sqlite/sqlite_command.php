<?php

namespace Phink\Data\Client\SQLite;

//require_once 'sqlite_data_reader.php';
//require_once 'sqlite_connection.php';
//require_once 'phink/data/command.php';
//require_once 'phink/data/crud_queries.php';
//require_once 'phink/core/object.php';

use Phink\Core\TObject;
use Phink\Data\ICommand;
use Phink\Data\TCrudQueries;
use Phink\Data\Client\SQLite\TSqliteConnection;
use Phink\Data\Client\SQLite\TSqliteDataReader;

/**
 * Description of aSqlitecommand
 *
 * @author david
 */
class TSqliteCommand extends TObject implements ICommand
{
    //put your code here
    private $_fieldCount;
    //private $_rowCount;
    private $_result;
    private $_reader;
    private $_queries;
    private $_activeConnection;
    private $_db = NULL;

    public function __construct(ASqliteConnection $activeConnection)
    {
        $this->_activeConnection = $activeConnection;
        $this->_queries = new TCrudQueries();
    }

    public function executeReader()
    {
        $this->_db = $this->_activeConnection->getState();

        $this->_result = $this->_db->query($this->_queries->getSelect());
        $this->_reader = new TSqliteDataReader($this->_result);

        return $this->_reader;
    }

    public function executeLimitedReader()
    {
        return $this->executeReaderPage(APage::pageNumber(), APage::pageCount());
    }

    public function executeReaderPage($page, $count)
    {
        $start = ($page - 1) * $count;
        $this->_queries->setSelect($this->_queries->getSelect() . " LIMIT $start, $count");
        return $this->executeReader();
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
            $this->_fieldCount = $this->_result->numColumns();
        }
        return $this->_fieldCount;
    }

    public function getRowCount()
    {
        return -1;

    }

    public function fieldName($i)
    {
        return $this->_result->columnName($i);
    }

    public function fieldType($i)
    {
        return $this->_result->columnType($i);
    }

    public function fieldLen($i)
    {
        return -1;
    }

    public function __destruct()
    {
        $this->_result->reset();
    }
}

?>
