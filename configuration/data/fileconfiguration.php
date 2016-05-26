<?php
namespace Phink\Configuration\Data;

//require_once 'phink/configuration/TConfiguration.php';

use Phink\Configuration\TConfiguration;

/**
 * Description of TFileConfiguration
 *
 * @author david
 */
class TFileConfiguration extends TConfiguration
{
    //put your code here
    private $_fileName;

    public function __construct($fileName)
    {
        parent::__construct($this);
        $this->_fileName = $fileName;

    }


    public function getFileName()
    {
        return $this->_fileName;
    }
}
