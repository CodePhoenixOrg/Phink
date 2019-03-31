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
 
 
namespace Phink\Data\Client\SqlServer;

//require_once 'sqlserver_configuration.php';
//require_once 'phink/data/connection.php';
//require_once 'phink/configuration/configurable.php';

use Phink\Core\TObject;
use Phink\Configuration\IConfigurable;
use Phink\Data\IConnection;
use Phink\Data\Client\SqlServer\TSqlServerConfiguration;

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
