<?php
namespace Phink\Configuration;

//require_once 'phink/core/acomponent.php';

use Phink\Declarators\THtmlDeclarator;
/**
 * Description of aconfig
 *
 * @author david
 */
abstract class TConfiguration extends \Phink\UI\TComponent
{
    public function __construct($parent)
    {
        parent::__construct($parent);
    }

    public function parse()
    {

        $preHtmlName = $this->getPreHtmlName();
        if(file_exists($preHtmlName)) {
            require ($preHtmlName);
            return true;
        }

        $this->_parse2();

        $templateName = $this->getTemplateName();

        if($this->isDirty() === true) {
            $templateName = $preHtmlName;
            file_put_contents($templateName, $this->contents);

        }

        require($templateName);

    }

    private function _parse2()
    {
        $jsonName = $this->getJsonName();
        if(file_exists($jsonName)) {
            $json = file_get_contents($jsonName);
            $this->_elements = unserialize($json);
            return true;
        }

        $configName = $this->getConfigName();
        $this->contents = file_get_contents($configName);

        $declarator = new THtmlDeclarator($this->contents);

        $this->_elements = $declarator->parse();

        $jsonElements = serialize($this->_elements);

        file_put_contents($jsonName, $jsonElements);


    }

}
