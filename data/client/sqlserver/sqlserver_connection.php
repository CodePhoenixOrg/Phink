<?php

namespace Phoenix\Data\Client\SqlServer;

//require_once 'sqlserver_configuration.php';
//require_once 'phoenix/data/connection.php';
//require_once 'phoenix/configuration/configurable.php';

use Phoenix\Core\TObject;
use Phoenix\Configuration\IConfigurable;
use Phoenix\Data\IConnection;
use Phoenix\Data\Client\SqlServer\TSqlServerConfiguration;

/**
 * Description of TMySqlConnection
 *
 * @author david
 */
class TSqlServerConnection extends TObject implements IConnection, IConfigurable
{

    private $_state = 0;
    private $_sqlConfig;

    public function __construct(TSqlServerConfiguration $sqlConfig)
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
            $this->_state = mssql_pconnect($this->_sqlConfig->getHost(), $this->_sqlConfig->getUser(), $this->_sqlConfig->getPassword());
            mssql_select_db($this->_sqlConfig->databaseName, $this->_state);
        } catch (Exception $ex) {
            exception($ex);
        }

        return $this->_state;
    }

    public function close()
    {
        mssql_close($this->_state);

        return $this->_state;
    }

    public function configure()
    {

    }
}
?>
