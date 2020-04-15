<?php
namespace Phink\Apps\QBE;

use Phink\Data\Client\PDO\TPdoConnection;
use Phink\MVC\TActionInfo;

class TQbeGrid extends \Phink\MVC\TPartialController
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
        $this->query = urldecode($this->request->getQueryArguments('query'));

        $this->cs = TPdoConnection::opener('niduslite_conf');
        self::getLogger()->sql($this->query);
        if(!empty($this->query)) {
            $this->stmt = $this->cs->query($this->query);
        }
    }

    public function setPageCount(int $value) : void
    {
        $this->pageCount = $value;
    }

    public function setAnchor(string $value) : void
    {
        $this->anchor = $value;
    }
    
    public function setOnclick(string $value) : void
    {
        $this->onclick = $value;
    }

    public function getData(int $pagecount, ?int $pagenum, string $query): TActionInfo
    {
        if ($this->stmt !== null && $this->stmt->hasException()) {
            $this->data = $this->stmt->getException()->getMessage();
        }

        $id = $this->getViewName();
        $this->data = \Phink\Web\UI\Widget\Plugin\TPlugin::getGridData($id, $this->stmt, $pagecount);
        // $this->response->setData('grid', $this->data);

        return TActionInfo::set($this, 'grid', $this->data);

    }

    
}
