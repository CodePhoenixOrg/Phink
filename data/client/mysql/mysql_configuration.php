<?php

namespace Phink\Data\Client\MySQL;

//require_once 'phink/configuration/data/sqlconfiguration.php';

use Phink\Configuration\Data\TDataConfiguration;

/**
 * Description of mysqlconfiguration
 *
 * @author david
 */
class TMySqlConfiguration extends TDataConfiguration
{
    public function __construct($driver, $host, $user, $password, $databaseName)
    {
        parent::__construct(\Phink\Data\TServerType::MYSQL, $host, $user, $password, $databaseName);
    }
    //put your code here
}
?>
