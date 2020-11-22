<?php

namespace Phink\Apps\QEd;

use Phink\Data\Client\PDO\TPdoConnection;
use Phink\Data\Client\PDO\TPdoDataStatement;
use Phink\MVC\TActionInfo;

class TQEdGrid extends \Phink\MVC\TPartialController
{
    protected $stmt = null;
    protected $items = [];
    protected $cs = null;
    protected $pager = null;
    protected $onclick = null;
    protected $anchor = null;
    protected $pageCount = 0;
    protected $index = 1;
    protected $data = '';
    protected $query = '';
    protected $grid0 = null;

    public function init(): void
    {
        $this->index = $this->request->getQueryArguments('pagenum');
        $this->pageCount = $this->request->getQueryArguments('pagecount');

        $this->cs = TPdoConnection::opener('niduslite_conf');
    }

    public function setPageCount(int $value): void
    {
        $this->pageCount = $value;
    }

    public function setAnchor(string $value): void
    {
        $this->anchor = $value;
    }

    public function setOnclick(string $value): void
    {
        $this->onclick = $value;
    }

    public function limitQuery($count, ?int $start, string $query): TPdoDataStatement
    {
        $result = null;
        $query = urldecode($query);
        $query = trim($query, ';');
        $query .= PHP_EOL . ' limit ' . (($start - 1) * $count) . ', ' . $count . ';'. PHP_EOL;

        self::getLogger()->sql($query);
        if (!empty($query)) {
            $result = $this->cs->query($query);
        }

        return $result;
    }

    public function getData(int $pagecount, ?int $pagenum, string $query): TActionInfo
    {
        $data = null;
        $this->stmt = $this->limitQuery($pagecount, $pagenum, $query);

        if ($this->stmt !== null && $this->stmt->hasException()) {
            $data = $this->stmt->getException()->getMessage();
        }

        $id = $this->getViewName();
        $data = \Phink\Web\UI\Widget\Plugin\TPlugin::getGridData($id, $this->stmt, $pagecount);

        return TActionInfo::set($this, 'grid', $data);
    }
}
