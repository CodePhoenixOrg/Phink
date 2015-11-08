<?php

namespace Phoenix\Data\Client\MySQL;

//require_once 'phoenix/configuration/data/sqlconfiguration.php';

use Phoenix\Configuration\Data\TDataConfiguration;

/**
 * Description of mysqlconfiguration
 *
 * @author david
 */
class TMySqlConfiguration extends TDataConfiguration
{
    public function __construct($driver, $host, $user, $password, $databaseName)
    {
        parent::__construct(\Phoenix\Data\TServerType::MYSQL, $host, $user, $password, $databaseName);
    }
    //put your code here
}
?>
