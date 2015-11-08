<?php

namespace Phoenix\Data\Client\Redis;

//require_once 'phoenix/data/connection.php';
//require_once 'phoenix/configuration/configurable.php';
//require_once 'pdo_configuration.php';

use Phoenix\Core\TObject;
use Phoenix\Configuration\IConfigurable;
use Phoenix\Data\IConnection;
use Phoenix\Data\TServerType;
use Phoenix\Data\Client\Redis\TRedisConfiguration;

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
            \Phoenix\Log\TLog::exception($ex, __FILE__, __LINE__);
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
