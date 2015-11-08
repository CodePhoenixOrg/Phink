<?php

namespace Phoenix\Data\Client\SqlServer;

//require_once 'phoenix/data/data_reader.php';
//require_once 'phoenix/core/aobject.php';

use Phoenix\Core\TObject;
use Phoenix\Data\IDataReader;
/**
 * Description of adatareader
 *
 * @author david
 */
class TSqlServerDataReader extends TObject implements IDataReader
{


    private $_result;
    private $_values;

    public function __construct($result)
    {
        $this->_result = $result;
    }

    public function values($i)
    {
        return $this->_values[$i];
    }


    public function read()
    {
        $this->_values = mssql_fetch_array($this->_result);
        if($this->_values > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
?>
