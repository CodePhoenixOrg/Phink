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
class TRestConnection extends TObject implements IConnection
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
            $this->getLogger()->debug($this->_state);

        } catch (\Throwable $ex) {
            throw new \Exception('An error occured while trying to open a connection on the URL ' . $this->_restConfig->getUrl(), -1, $ex);
        } catch (\Exception $ex) {
            throw new \Exception('An error occured while trying to open a connection on the URL ' . $this->_restConfig->getUrl(), -1, $ex);
        }

        return $this->_state;
    }

    public function close()
    {
        unset($this->_state);
        return NULL;
    }


    public function getDriver()
    {
        
    }
}
