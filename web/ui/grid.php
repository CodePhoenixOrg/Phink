<?php
namespace Phoenix\Web\UI;

/**
 * Description of tgrid
 *
 * @author david
 */
class TGrid extends \Phoenix\MVC\TPartialController
{
    use \Phoenix\Web\UI\THtmlPattern;
    use \Phoenix\Data\UI\TDataBinder;
    
    protected static $templateFilename;

    public function renderHtml()
    {
        $this->getElements();
        $elements = $this->elements[$this->getPattern()];
        $algoClass = '\Phoenix\Web\UI\Algo\T' . ucfirst($this->getPattern());
        $algo = new $algoClass();
        $algo->setCols($this->columns);
        $algo->setRows($this->rows);
        $algo->setData($this->data);
        $algo->setPivot($this->pivot);
        $algo->setTemplates($this->templates);
        $algo->setElements($elements);
        $this->innerHtml = $algo->run();
    }

}
