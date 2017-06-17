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
    
    public function getDriver()
    {
        
    }
}
?>
