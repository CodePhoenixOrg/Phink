<?php

namespace Phink\Data\Client\MySQL;

//require_once 'phink/data/data_reader.php';
//require_once 'phink/core/object.php';

use Phink\Core\TObject;
use Phink\Data\IDataReader;

/**
 * Description of adatareader
 *
 * @author david
 */
class TMySqlDataReader extends TObject implements IDataReader
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
        $this->_values = mysql_fetch_array($this->_result);
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
