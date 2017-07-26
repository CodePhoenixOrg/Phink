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
 
 
namespace Phink\Data\Client\Rest;

//require_once 'phink/configuration/configurable.php';
//require_once 'phink/data/connection.php';
//require_once 'jsonite_configuration.php';

use Phink\Core\TObject;
use Phink\Configuration\IConfigurable;
use Phink\Data\IConnection;
use Phink\Data\Client\Rest\TRestConfiguration;

/**
 * Description of aRestconnection
 *
 * @author david
 */
class TRestConnection extends TObject implements IConnection, IConfigurable
{

    private $_restConfig;
    private $_state = NULL;

    public function __construct(TRestConfiguration $jsonConfig)
    {
        $this->_restConfig = $jsonConfig;
    }

    public function getState()
    {
        return $this->_state;
    }

    public function open()
    {
        try {
            $this->_state =  new \Phink\Web\TCurl();
            $this->_state->request($this->_restConfig->getUrl());

        } catch (\Throwable $ex) {
            $this->getLogger()->exception($ex);
        } catch (\Exception $ex) {
            $this->getLogger()->exception($ex);
        }

        return $this->_state;
    }

    public function close()
    {
        unset($this->_state);
        return NULL;
    }

    public function getConfiguration()
    {
        return $this->_restConfig;
    }
    
    public function configure()
    {

    }
    
    public function getDriver()
    {
        
    }
}
