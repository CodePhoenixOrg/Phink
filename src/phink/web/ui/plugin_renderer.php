<?php
/*
 * Copyright (C) 2016 David Blanchard
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
 * Description of tgrid
 *
 * @author david
 */
abstract class TPluginRenderer extends \Phink\MVC\TPartialController
{
    use \Phink\Web\UI\THtmlPattern;
    use \Phink\Data\UI\TDataBinder;
    use \Phink\Web\UI\THtmlControl;
    
    protected static $templateFilename;

    public function renderHtml() :  void
    {
//        if($this->data !== null) {
//            $this->columns = $this->data['cols'];
//            $this->rows = $this->data['rows'];
//            $elements = $this->data['elements'];
//            $this->templates = $this->data['templates'];
//        } else {
            $this->getElements();
            $elements = $this->elements[$this->getPattern()];
            //self::$logger->dump('PATTERN ELEMENTS', $elements);

            $id = $this->getParent()->getId();

            $result = array();
            $c = count($elements);
            for($i = 0; $i < $c; $i++) {
                array_push($result, ['opening' => $elements[$i]->getOpening(), 'closing' => $elements[$i]->getClosing()]);
            }

            $json = json_encode($result);
            $elementsFilename = RUNTIME_JS_DIR . $id . '_elements.json';
            file_put_contents($elementsFilename, $json);
//        }
        
        if($this->rows === PAGE_COUNT_ZERO) {
            $this->rows = count($this->data['values']);
        }
        
        $pluginClass = '\Phink\Web\UI\Plugin\T' . ucfirst($this->getPattern());
        $plugin = new $pluginClass($this);
        $plugin->setCss($this->css);
        $plugin->setContent($this->content);
        $plugin->setEvent($this->event);
        $plugin->setEnabled($this->enabled);
        $plugin->setId($this->getId());
        $plugin->setCols($this->columns);
        $plugin->setRows($this->rows);
        $plugin->setData($this->data);
        $plugin->setPivot($this->pivot);
        $plugin->setTiled($this->tileBy);
        $plugin->setTemplates($this->templates);
        $plugin->setElements($elements);
        $this->innerHtml = $plugin->render();
    }

}
