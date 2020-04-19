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
 * GNU General Public License forThis more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Phink\Widgets\Pager;

use Phink\TAutoloader;
use Phink\Registry\TRegistry;
use Phink\Web\UI\Widget\TWidget;

class TPager extends TWidget
{

    protected $pageCount;
    protected $currentPage;
    protected $pageNum;
    protected $pagerJS;
    protected $jscall;
    protected $script;

    public function setPageCount($value): void
    {
        $this->pageCount = $value;
    }

    public function setCurrentPage($value): void
    {
        $this->currentPage = $value;
    }

    public function setPageNum($value): void
    {
        $this->pageNum = $value;
    }

    public function partialLoad(): void
    {
        $forControl = $this->parent->getChildById($this->forThis);

        $this->forView = ($this->getMotherView() !== null) ? $this->getMotherView()->getViewName() : $this->parent->getViewName();
        $this->forCtrl = $this->parent->getViewName();
        $this->forApp = $this->getApplication()->getName() . 'App';

        $this->pageNum = (int) (!$this->pageNum) ? 1 : $this->pageNum;
        $this->pageCount = ($forControl) ? $forControl->getRowCount() : $this->pageNum;

        $this->path = TRegistry::widgetPath('TPager');

        $pagerJS = file_get_contents($this->path . '/views/pager.jhtml');
        $pagerJS = str_replace('{{ forApp }}', $this->forApp, $pagerJS);
        $pagerJS = str_replace('{{ forThis }}', $this->forThis, $pagerJS);
        $pagerJS = str_replace('{{ forView }}', $this->forView, $pagerJS);
        $pagerJS = str_replace('{{ forCtrl }}', $this->forCtrl, $pagerJS);
        $pagerJS = str_replace('{{ pageCount }}', $this->pageCount, $pagerJS);
        $pagerJS = str_replace('{{ pageNum }}', $this->pageNum, $pagerJS);
        $pagerJS = str_replace('{{ id }}', $this->id, $pagerJS);
        $pagerJS = str_replace('{{ onclick }}', $this->onclick, $pagerJS);

        $this->cacheJsFile($this->forThis . 'pager', $pagerJS);
    }
}
