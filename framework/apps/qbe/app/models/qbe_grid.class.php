<?php


namespace Phink\Apps\QBE\Models;

use Phink\Data\Client\PDO\TPdoConnection;
// require_once APP_DATA . 'amarok_connection.php';


class TQbeGrid extends \Phink\MVC\TModel
{
    public function init(): void
    {
        $this->connector = TPdoConnection::opener('niduslite_conf');
    }

    public function __destruct()
    {
        $this->connector->close();
    }
}
