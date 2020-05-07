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

namespace Phink\Web\UI\Widget\Plugin;

class TPlugin extends TPluginRenderer
{
    use \Phink\Data\UI\TDataTag;

    private $_children = [];
    private $_rowCount;
    private $_pageNum;
    private $_childrenCount;
    protected $contents = null;
    protected $hasChildren = false;

    public function getChildren(): ?array
    {
        return $this->_children;
    }
    public function setChildren(array $value): void
    {
        $this->_children = $value;
    }

    public function getChildrenCount(): int
    {
        return $this->_childrenCount;
    }

    public function getRowCount(): int
    {
        return $this->_rowCount;
    }
    public function setRowCount(int $value): void
    {
        $this->_rowCount = $value;
    }

    public function getPageNum(): int
    {
        return $this->_pageNum;
    }
    public function setPageNum(int $value): void
    {
        $this->_pageNum = $value;
    }

    public function init(): void
    {
        $this->_childrenCount = count($this->_children);
        $this->hasChildren = $this->_childrenCount > 0;

        if ($this->hasChildren) {
            $this->templates = $this->getControls($this->_children);
            $id = $this->getParent()->getId();

            $json = json_encode($this->templates);
            $templateFilename = RUNTIME_JS_DIR . $id . '_template.json';
            file_put_contents($templateFilename, $json);

            $this->_children = $this->arrayProperty($this->_children, 'name');
            $this->_childrenCount = count($this->_children);
        } else {
            $this->_childrenCount = $this->statement->getFieldCount();
        }

        $this->_rowCount = $this->pageCountByDefault($this->_rowCount);
    }

    // public function createObjects()
    // {
    //     $this->setId("plugin");
    // }

    // public function declareObjects()
    // {
    //     $this->setPattern($this->getPattern());
    //     $this->setCols($this->getChildrenCount());
    //     $this->setRows($this->getRowCount());
    //     $this->addChild($this);
    // }

    public static function getGridData($id, ?\Phink\Data\IDataStatement $stmt, $rowsPerPage = 1): array
    {

        $fieldCount = 0;
        $names = [];
        $values = [];
        $templates = [];

        $templateFilename = RUNTIME_JS_DIR . $id . '_template.json';
        if (file_exists($templateFilename)) {
            $templates = json_decode(file_get_contents($templateFilename));
        }

        $elementsFilename = RUNTIME_JS_DIR . $id . '_elements.json';
        $elements = '';
        if (file_exists($elementsFilename)) {
            $elements = json_decode(file_get_contents($elementsFilename));
        }

        if ($stmt !== null) {

            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $rowCount = count($rows);

            if ($rowCount > 0) {
                $names = array_keys($rows[0]);
                $fieldCount = count($names);
            }

            foreach ($rows as $row) {
                $row = array_values($row);
                // array_push($values, json_encode($row));
                array_push($values, $row);
            }
            for ($k = $rowCount; $k < $rowsPerPage; $k++) {
                // array_push($values, json_encode(array_fill(0, $fieldCount, '&nbsp;')));
                array_push($values, array_fill(0, $fieldCount, '&nbsp;'));
            }
        }

        $result = [
            'cols' => $fieldCount
            , 'rows' => $rowsPerPage
            , 'names' => $names
            , 'values' => $values
            , 'templates' => $templates,
        ];

        if (isset($elements)) {
            $result['elements'] = $elements;
        }

        return $result;
    }

    public function getData(): void
    {
        $this->data = self::getGridData($this->getParent()->getViewName(), $this->statement, $this->_rowCount);
        $this->response->setData('data', $this->data);
    }

    public function renderHtml(): void
    {
        $this->data = self::getGridData($this->getParent()->getViewName(), $this->statement, $this->_rowCount);

        $id = $this->getParent()->getViewName();
        $scriptFilename = $id . '_data.js';
        $json = json_encode($this->data);
        file_put_contents(RUNTIME_JS_DIR . $scriptFilename, 'var ' . $id . 'Data = ' . $json . ';');
        file_put_contents(RUNTIME_JS_DIR . $id . '_data.json', $json);

        $this->response->addJSObject(REL_RUNTIME_JS_DIR . $scriptFilename);

        parent::renderHtml();

        $this->isRendered = true;
    }
}
