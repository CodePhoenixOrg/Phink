<?php

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

    public function getEnabled()
    {
        return $this->enabled;
    }
    public function setEnabled($value)
    {
        $this->enabled = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public function getName()
    {
        return $this->name;
    }
    public function setName($value)
    {
        $this->name = $value;
    }

    public function getImage()
    {
        return $this->image;
    }
    public function setImage($value)
    {
        $this->image = $value;
    }

    public function getCss()
    {
        return $this->css;
    }
    public function setCss($value)
    {
        $this->css = $value;
    }

    public function getContent()
    {
        return $this->content;
    }
    public function setContent($value)
    {
        $this->content = $value;
        if(isset($this->content[0]) && $this->content[0] === '@') {
            $templateName = str_replace(PREHTML_EXTENSION, '', substr($this->content,1));
            $templateName = 'app' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $templateName . DIRECTORY_SEPARATOR . $templateName . PREHTML_EXTENSION;

            if(file_exists($templateName)) {
                $contents = file_get_contents($templateName, FILE_USE_INCLUDE_PATH);
                $this->content = $contents;
            }
        }
    }
    
    public function getDragHelper()
    {
        return $this->dragHelper;
    }
    public function setDragHelper($value)
    {
        $this->dragHelper = $value;
        if($this->dragHelper[0] == '@') {
            $templateName = str_replace(PREHTML_EXTENSION, '', substr($this->dragHelper,1));
            $templateName = 'app' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $templateName . DIRECTORY_SEPARATOR . $templateName . PREHTML_EXTENSION;

            if(file_exists($templateName)) {
                $contents = file_get_contents($templateName, FILE_USE_INCLUDE_PATH);
                $this->dragHelper = $contents;
            }
        }
    }
    
    public function getEvent()
    {
        return $this->event;
    }
    public function setEvent($value)
    {
        $this->event = $value;
    }

    public function getProperties()
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
    
    public function sleep()
    {
        $object = serialize($this->getControl());
    }
}
