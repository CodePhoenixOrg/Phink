<?php
/*
 * Copyright (C) 2019 David Blanchard
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
 
 
namespace Phink\Data\Client\Redis;

//require_once 'phink/data/connection.php';
//require_once 'phink/configuration/configurable.php';
//require_once 'pdo_configuration.php';

use Phink\Core\TObject;
use Phink\Configuration\IConfigurable;
use Phink\Data\IConnection;
use Phink\Data\TServerType;
use Phink\Data\Client\Redis\TRedisConfiguration;

use Predis\Client;
/**
 * Description of TPdoConnection
 *
 * @author david
 */
class TRedisConnection extends TObject implements IConnection, IConfigurable
{

    private $_state = 0;
    private $_config;
    private $_dsn;
    private $_params;

    public function __construct(TRedisConfiguration $config)
    {
        $this->_config = $config;
        $this->configure();
    }

    public function getState()
    {
        return $this->_state;
    }

    public function open()
    {
        try {
            $this->_state = new Client($this->_params);
        } catch (\Predis\PredisException $ex) {
            //self::$logger->exception($ex, __FILE__, __LINE__);
        }

        return $this->_state;
    }

    public function configure()
    {
        $this->_params = ['host' => $this->_config->getHost(), 'port' => $this->_config->getPort(), 'database' => $this->_config->getDatabaseName()]; 

    }
    
    public function setAttribute($key, $value)
    {
        $this->_state->setAttribute($key, $value);
    }
    
    public function getAttribute($key)
    {
        return $this->_state->getAttribute($key);
    }
    
    public function close()
    {
        unset($this->_state);

        return $this->_state;
    }

    public function __destruct()
    {
    }
    
}
