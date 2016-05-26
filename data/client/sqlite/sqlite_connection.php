<?php

namespace Phink\Data\Client\SQLite;

//require_once 'phink/configuration/configurable.php';
//require_once 'phink/data/connection.php';
//require_once 'sqlite_configuration.php';

use Phink\Core\TObject;
use Phink\Configuration\IConfigurable;
use Phink\Data\IConnection;
use Phink\Data\Client\SQLite\TSqliteConfiguration;

/**
 * Description of aSqliteconnection
 *
 * @author david
 */
class TSqliteConnection extends TObject implements IConnection, IConfigurable
{

    private $_sqlConfig;
    private $_state = NULL;

    public function __construct(ASqliteConfiguration $sqlConfig)
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
            $this->_state = new SQLite3($this->_sqlConfig->getFileName());
        } catch (Exception $ex) {
            exception($ex);
        }

        return $this->_state;
    }

    public function close()
    {
        $this->_state->close();
        $this->_state = NULL;
        return NULL;
    }

    public function configure()
    {

    }
}
?>
