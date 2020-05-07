<?php


namespace Phink\Apps\QBE\Models;

use Phink\Data\Client\PDO\TPdoConnection;
use Phink\Data\Client\PDO\TPdoDataStatement;

class TQbeGrid extends \Phink\MVC\TModel
{
    public function init(): void
    {
        $this->connector = TPdoConnection::opener('niduslite_conf');
    }

    public function limitQuery($count, ?int $start, string $query): TPdoDataStatement
    {
        $result = null;
        $query = urldecode($query);
        $query = trim($query, ';');
        $query .= PHP_EOL . ' limit ' . (($start - 1) * $count) . ', ' . $count . ';' . PHP_EOL;

        self::getLogger()->sql($query);
        if (!empty($query)) {
            $result = $this->connector->query($query);
        }

        return $result;
    }
    
    public function __destruct()
    {
        $this->connector->close();
    }
}
