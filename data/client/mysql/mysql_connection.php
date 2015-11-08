<?php

namespace Phoenix\Data\Client\MySQL;

//require_once 'phoenix/data/connection.php';
//require_once 'phoenix/configuration/configurable.php';
//require_once 'mysql_configuration.php';

use Phoenix\Core\TObject;
use Phoenix\Configuration\IConfigurable;
use Phoenix\Data\IConnection;
use Phoenix\Data\Client\MySQL\TMySqlConfiguration;

/**
 * Description of TMySqlConnection
 *
 * @author david
 */
class TMySqlConnection extends TObject implements IConnection, IConfigurable
{

    private $_state = 0;
    private $_sqlConfig;

    public function __construct(TMySqlConfiguration $sqlConfig)
    {
        $this->_sqlConfig = $sqlConfig;
    }

    public function getState()
    {
        return $this->_state;
    }

    public function open()
    {
        try {
            $this->_state = mysql_connect($this->_sqlConfig->getHost(), $this->_sqlConfig->getUser(), $this->_sqlConfig->getPassword());
            mysql_select_db($this->_sqlConfig->getDatabaseName(), $this->_state);
        } catch (Exception $ex) {
            exception($ex);
        }

        return $this->_state;
    }

    public function close()
    {
        mysql_close($this->_state);

        return $this->_state;
    }

    public function configure()
    {

    }
}
?>
