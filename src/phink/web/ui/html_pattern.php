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

namespace Phink\Web\UI;

/**
 * Description of THtmlPattern
 *
 * @author david
 */
trait THtmlPattern
{
    //put your code here
    protected $elements = [];
    protected $pattern = '';

    public function getElements(): array
    {
        if (count($this->elements) == 0) {
            $this->_getElements();
        }
        return $this->elements;
    }
    public function setElements(array $value): void
    {
        $this->elements = $value;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }
    public function setPattern(string $value): void
    {
        $this->pattern = $value;
    }

    public function getPatternElements(): string
    {
        return PHINK_PLUGINS_ROOT . $this->pattern . DIRECTORY_SEPARATOR . $this->pattern . PREHTML_EXTENSION;
    }

    private function _getElements(): void
    {
        $type = $this->getType();
        $path = ROOT_PATH . \Phink\Registry\TRegistry::classPath($type);

        $patternElements = $this->getPatternElements();

        $jsonName = RUNTIME_JS_DIR . 'pattern_' . $this->pattern . JSON_EXTENSION;
        if (file_exists($jsonName)) {
            $json = file_get_contents($jsonName);
            $this->elements = unserialize($json);
            return;
        }

        //self::$logger->dump('PATTERN NAME', $patternElements);
        $contents = file_get_contents($patternElements);

        $doc = new \Phink\Xml\TXmlDocument($contents);
        $doc->matchAll();
        $elements = $doc->getList();

        foreach ($elements as $element) {

            $key = $element['properties']['id'];
            if (!isset($this->elements[$key])) {
                $this->elements[$key] = array();
            }

            array_push($this->elements[$key]
                , new THtmlElement($element['id']
                    , $element['properties']['pattern']
                    , $element['properties']['rule'])
            );
        }

        $jsonElements = serialize($this->elements);

        file_put_contents($jsonName, $jsonElements);

    }

}
