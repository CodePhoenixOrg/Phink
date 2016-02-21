<?php
namespace Phoenix\Web\UI;

/**
 * Description of tgrid
 *
 * @author david
 */
class TAlgoDispatcher extends \Phoenix\MVC\TPartialController
{
    use \Phoenix\Web\UI\THtmlPattern;
    use \Phoenix\Data\UI\TDataBinder;
    
    protected static $templateFilename;

    public function renderHtml()
    {
//        if($this->data !== null) {
//            $this->columns = $this->data['cols'];
//            $this->rows = $this->data['rows'];
//            $elements = $this->data['elements'];
//            $this->templates = $this->data['templates'];
//        } else {
            $this->getElements();
            $elements = $this->elements[$this->getPattern()];
            \Phoenix\Log\TLog::dump('PATTERN ELEMENTS', $elements);

            $id = $this->getParent()->getId();

            $result = array();
            $c = count($elements);
            for($i = 0; $i < $c; $i++) {
                array_push($result, ['opening' => $elements[$i]->getOpening(), 'closing' => $elements[$i]->getClosing()]);
            }

            $json = json_encode($result);
            $elementsFilename = TMP_DIR . DIRECTORY_SEPARATOR . $id . '_elements.json';
            file_put_contents($elementsFilename, $json);
//        }
        
        
        
        $algoClass = '\Phoenix\Web\UI\Algo\T' . ucfirst($this->getPattern());
        $algo = new $algoClass();
        $algo->setCols($this->columns);
        $algo->setRows($this->rows);
        $algo->setData($this->data);
        $algo->setPivot($this->pivot);
        $algo->setTemplates($this->templates);
        $algo->setElements($elements);
        $this->innerHtml = $algo->render();
    }

}
