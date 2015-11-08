<?php

namespace Phoenix\Text;


/**
 * Description of aregex
 *
 * @author david
 */
class TRegex extends \Phoenix\Core\TObject
{

    private $_pattern = '';
    private $_text = '';
    private $_matches = NULL;
    private $_id = -1;
    private $_count = 0;
    private $_groupCount = 0;
    private $_match = NULL;
    private $_offset = 0;

    public function  __construct($pattern, $text)
    {
        $this->_pattern = $pattern;
        $this->_text = $text;
    }

    public function match()
    {
        preg_match($this->_pattern, $this->_text, $this->_matches, PREG_OFFSET_CAPTURE);
        $this->_groupCount = count($this->_matches);
        $this->_count = count($this->_matches);
        return ($this->_count > 0);

    }

    public function matchAll()
    {
        preg_match_all($this->_pattern, $this->_text, $this->_matches, PREG_OFFSET_CAPTURE);
        $this->_groupCount = count($this->_matches);
        $this->_count = count($this->_matches[0]);
        return ($this->_count > 0);

    }

    public function debug()
    {
        print_r($this->_matches);
    }

    public function reset()
    {
        $this->_id = 0;
    }

    public function getCount()
    {
        return $this->_count;
    }

    public function getText()
    {
        return $this->_text;
    }

    public function getMatch()
    {
        if($this->_match == NULL) {
            $groups = NULL;
            $matchText = $this->_matches[0][$this->_id][0];

            for($i = 1; $i < $this->_groupCount; $i++) {
                $groups[$i - 1] = $this->_matches[$i][$this->_id][0];
            }

            $this->_match = new TRegexMatch($matchText, $groups, $this->_matches[0][$this->_id][1]);
        }

        return $this->_match;
    }

    public function nextMatch()
    {
        $this->_match = NULL;
        if($this->_id == $this->_count - 1) {
            return false;
        }

        $this->_id++;
        
        return ($this->getMatch() != NULL);
    }

    public function replaceMatch($replace)
    {
        
        $str_begin = substr($this->_text, 0, $this->_match->getPosition() + $this->_offset);
        $str_end = substr($this->_text, $this->_match->getPosition() + $this->_offset + strlen($this->_match->getText()) );
        $this->_offset += strlen($replace) - strlen($this->_match->getText());
        $this->_text = $str_begin . $replace . $str_end;

        //$this->_text = str_replace($this->_match->text, $replace, $this->_text);

        return $this->_text;
    }
    
    


}
?>
