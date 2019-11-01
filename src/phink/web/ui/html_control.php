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
 
 
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Phink\Web\UI;

/**
 * Description of html_control
 *
 * @author David
 */
trait THtmlControl 
{
    //put your code here
    protected $css = '';
    protected $name = '';
    protected $image = '';
    protected $content = '';
    protected $dragHelper = '';
    protected $enabled = true;
    protected $event = '';

    public function getEnabled() : bool
    {
        return $this->enabled;
    }
    public function setEnabled($value) : void
    {
        $this->enabled = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public function getName() : string
    {
        return $this->name;
    }
    public function setName($value) : void
    {
        $this->name = $value;
    }

    public function getImage() : string
    {
        return $this->image;
    }
    public function setImage($value) : void
    {
        $this->image = $value;
    }

    public function getCss() : string
    {
        return $this->css;
    }
    public function setCss($value) : void
    {
        $this->css = $value;
    }

    public function getContent() : string
    {
        return $this->content;
    }
    public function setContent($value) : void
    {
        $this->content = $value;
        
        // self::$logger->debug(__CLASS__ . '::' . __METHOD__ .'::HTML TEMPLATE CONTENT : <pre>[' . PHP_EOL . htmlentities($this->content) . PHP_EOL . '...]</pre>');

        if(isset($this->content[0]) && $this->content[0] === '@') {
            $templateName = str_replace(PREHTML_EXTENSION, '', substr($this->content,1));
            $templateName = SRC_ROOT . 'app' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $templateName . PREHTML_EXTENSION;

            if(file_exists($templateName)) {
                $contents = file_get_contents($templateName);
                $this->content = $contents;
            }
        }
    }
    
    public function getDragHelper() : string
    {
        return $this->dragHelper;
    }
    public function setDragHelper($value): void
    {
        $this->dragHelper = $value;
        if($this->dragHelper[0] == '@') {
            $templateName = str_replace(PREHTML_EXTENSION, '', substr($this->dragHelper,1));
            $templateName = SRC_ROOT . 'app' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $templateName . PREHTML_EXTENSION;

            if(file_exists($templateName)) {
                $contents = file_get_contents($templateName);
                $this->dragHelper = $contents;
            }
        }
    }
    
    public function getEvent() : string
    {
        return $this->event;
    }
    public function setEvent($value) : void
    {
        $this->event = $value;
    }

    public function getProperties() : array
    {
        return [
            'image' => $this->image
          , 'name' => $this->name
          , 'css' => $this->css
          , 'event' => $this->event
          , 'content' => $this->content
          , 'dragHelper' => $this->dragHelper
          , 'enabled' => $this->enabled
        ];
    }
    
    public function getControl() 
    {
        return (object) $this->getProperties();
    }
    
    public function sleep() : void
    {
        $object = serialize($this->getControl());
    }
}
