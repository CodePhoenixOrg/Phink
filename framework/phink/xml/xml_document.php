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
Possible regex to replace the strpos based method stuff
$re = '/(<phx:element.[^>]+?[^\/]>)(.*?)(<\/phx:element>)|(<phx:.+?>)|(<\/phx:\w+>)/is';
preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);

 */

namespace Phink\Xml;

use Phink\Core\TObject;

/**
 * Description of axmldocument
 *
 * @author david
 */
define('QUOTE', '"');
define('OPEN_TAG', '<');
define('CLOSE_TAG', '>');
define('TERMINATOR', '/');
define('TAB_MARK', "\t");
define('LF_MARK', "\n");
define('CR_MARK', "\r");
define('SKIP_MARK', '!');
define('QUEST_MARK', '?');
define('STR_EMPTY', '');
define('STR_SPACE', ' ');
define('TAG_PATTERN_ANY', "phx:");

class TXmlElementPos
{
    const None = 0;
    const Open = 1;
    const Close = 2;
}

class TXmlDocument extends TObject
{
    private $_count = 0;
    private $_cursor = 0;
    private $_matches = [];
    private $_text = STR_EMPTY;
    private $_id = -1;
    private $_match = null;
    private $_list = [];
    private $_depths = [];
    private $_matchesByDepth = [];
    private $_endPos = -1;

    public function __construct($text)
    {
        $this->_text = $text . OPEN_TAG . TAG_PATTERN_ANY . 'eof' . STR_SPACE . TERMINATOR . CLOSE_TAG;

        $this->_endPos = strlen($this->_text);
    }

    public function getMatches(): array
    {
        return $this->_matches;
    }

    public function getCount(): int
    {
        return $this->_count;
    }

    public function getCursor(): int
    {
        return $this->_cursor;
    }

    public function getList(): array
    {
        return $this->_list;
    }

    public function fieldValue(int $i, string $field, string $value)
    {
        $this->_list[$i][$field] = $value;
    }

    public function getMaxDepth(): int
    {
        return count($this->_depths);
    }

    public function getMatchesByDepth(): array
    {
        return $this->_matchesByDepth;
    }

    public function elementName(string $s, int $offset): string
    {
        if (!isset($offset)) {
            $offset = 0;
        }
        $result = STR_EMPTY;
        $s2 = STR_EMPTY;

        $openElementPos = 0;
        $closeElementPos = 0;
        $spacePos = 0;

        if ($offset > 0 && $offset < strlen($s)) {
            //$openElementPos = $offset;
            $openElementPos = strpos($s, OPEN_TAG, $offset);
        } else {
            $openElementPos = strpos($s, OPEN_TAG);
        }

        if ($openElementPos == -1) {
            return $result;
        }

        $s2 = substr($s, $openElementPos, strlen($s) - $openElementPos);
        $spacePos = strpos($s2, STR_SPACE);
        $closeElementPos = strpos($s2, CLOSE_TAG);
        if ($closeElementPos > -1 && $spacePos > -1) {
            if ($closeElementPos < $spacePos) {
                $result = substr($s, $openElementPos + 1, $closeElementPos - 1);
            } else {
                $result = substr($s, $openElementPos + 1, $spacePos - 1);
            }
        } elseif ($closeElementPos > -1) {
            $result = substr($s, $openElementPos + 1, $closeElementPos - 1);
        }

        return $result;
    }

    public function getMatch(): ?TXmlMatch
    {
        if ($this->_match == null) {
            //$this->_match = new TXmlMatch($this->_list[$this->_matchesByDepth[$this->_id]]);
            $this->_match = new TXmlMatch($this->_list[$this->_id]);
        }

        return $this->_match;
    }

    public function nextMatch(): ?TXmlMatch
    {
        $this->_match = null;
        if ($this->_id == $this->_count - 1) {
            return null;
        }

        $this->_id++;

        return $this->getMatch();
    }

    public function replaceMatch(string $replace): string
    {
        if ($this->_match->hasChildren()) {
            $start = $this->_match->getStart();
            $length = $this->_match->getEnd() - $this->_match->getStart() + 1;
            $needle = substr($this->_text, $start, $length);
            $this->_text = str_replace($needle, $replace, $this->_text);
        } else {
            $this->_text = str_replace($this->_match->getText(), $replace, $this->_text);
        }

        return $this->_text;
    }

    public function replaceThisMatch(TXmlMatch $match, string $text, string $replace): string
    {

        if ($match->hasChildren()) {
            $start = $match->getStart();
            $closer = $match->getCloser();
            $length = $closer['endsAt'] - $match->getStart() + 1;
            $needle = substr($text, $start, $length);
            $text = str_replace($needle, $replace, $text);
        } else {
            $text = str_replace($match->getText(), $replace, $text);
        }

        return $text;
    }

    private function _parse(string $tag, string $text, string $cursor): array
    {
        $properties = [];

        $endElementPos = strpos($text, OPEN_TAG . TERMINATOR . $tag, $cursor);
        $openElementPos = strpos($text, OPEN_TAG . $tag, $cursor);
        if ($openElementPos > -1 && $endElementPos > -1 && $openElementPos > $endElementPos) {
            $openElementPos = $endElementPos;
            $closeElementPos = strpos($text, CLOSE_TAG, $openElementPos);
            return [$openElementPos, $closeElementPos, $properties];
        }

        $spacePos = strpos($text, STR_SPACE, $openElementPos);
        $equalPos = strpos($text, '=', $spacePos);
        $openQuotePos = strpos($text, QUOTE, $openElementPos);
        $closeQuotePos = strpos($text, QUOTE, $openQuotePos + 1);
        $lastCloseQuotePos = $closeQuotePos;
        $closeElementPos = strpos($text, CLOSE_TAG, $lastCloseQuotePos);
        while ($openQuotePos > -1 && $closeQuotePos < $closeElementPos) {
            $key = substr($text, $spacePos + 1, $equalPos - $spacePos - 1);
            $value = substr($text, $openQuotePos + 1, $closeQuotePos - $openQuotePos - 1);
            $properties[trim($key)] = $value;
            $lastCloseQuotePos = $closeQuotePos;

            $spacePos = strpos($text, STR_SPACE, $closeQuotePos);
            $equalPos = strpos($text, '=', $spacePos);
            $openQuotePos = strpos($text, QUOTE, $closeQuotePos + 1);
            $closeQuotePos = strpos($text, QUOTE, $openQuotePos + 1);
            $closeElementPos = strpos($text, CLOSE_TAG, $lastCloseQuotePos);
            if ($openQuotePos < $closeElementPos && $closeQuotePos > $closeElementPos) {
                $closeElementPos = strpos($text, CLOSE_TAG, $closeQuotePos);
            }
        }
        if ($lastCloseQuotePos > -1) {
            $closeElementPos = strpos($text, CLOSE_TAG, $lastCloseQuotePos);
        } else {
            $closeElementPos = strpos($text, CLOSE_TAG, $openElementPos);
        }

        return [$openElementPos, $closeElementPos, $properties];
    }

    public function matchAll(string $tag = TAG_PATTERN_ANY): bool
    {
        $i = 0;
        $j = -1;

        $s = STR_EMPTY;
        $firstName = STR_EMPTY;
        $secondName = STR_EMPTY;

        $cursor = 0;

        $text = $this->_text;

        $result = $this->_parse($tag, $text, $cursor);
        $openElementPos = $result[0];
        $closeElementPos = $result[1];
        $properties = $result[2];

        $parentId[0] = -1;

        $depth = 0;
        //$this->_depths[$depth] = 1;

        while ($openElementPos > -1 && $closeElementPos > $openElementPos) {
            $s = trim(substr($text, $openElementPos, $closeElementPos - $openElementPos + 1));
            $firstName = $this->elementName($s, $cursor);

            $arr = explode(':', $firstName);

            if ($arr[1] == 'eof') {
                break;
            }

            $this->_list[$i]['id'] = $i;
            $this->_list[$i]['method'] = $arr[1];
            $this->_list[$i]['element'] = $s;
            $this->_list[$i]['name'] = $arr[1];
            $this->_list[$i]['startsAt'] = $openElementPos;
            $this->_list[$i]['endsAt'] = $closeElementPos;
            $this->_list[$i]['depth'] = $depth;
            $this->_list[$i]['hasCloser'] = false;
            $this->_list[$i]['childName'] = '';
            if (!isset($parentId[$depth])) {
                $parentId[$depth] = $i - 1;
            }
            $this->_list[$i]['parentId'] = $parentId[$depth];

            $p = strpos($s, STR_SPACE);
            // if ($p == strlen($firstName) + 1 && $closeElementPos > $p) {
            //     $attributes = trim(substr($s, $p + 1, strlen($s) - $p - 3));
            //     $this->_list[$i]['properties'] = TStringUtils::parameterStringToArray($attributes);
            // }

            $this->_list[$i]['properties'] = $properties;

            $cursor = $closeElementPos + 1;
            $secondName = $this->elementName($text, $cursor);

            if (TERMINATOR . $firstName != $secondName) {
                if ($s[1] == TERMINATOR) {
                    $depth--;
                    $this->_list[$i]['depth'] = $depth;
                    $pId = $this->_list[$i]['parentId'];
                    $this->_list[$pId]['hasCloser'] = true;
                    if ($this->_list[$pId]['depth'] > 0 && (empty($this->_list[$pId]['properties']['content']))) {
                        $contents = substr($text, $this->_list[$pId]['endsAt'] + 1, $this->_list[$i]['startsAt'] - $this->_list[$pId]['endsAt'] - 1);
                        $this->_list[$pId]['properties']['content'] = '!#base64#' . base64_encode($contents); // uniqid();
                    }

                    $this->_list[$pId]['closer'] = $this->_list[$i];
                    unset($this->_list[$i]);
                } elseif ($s[1] == QUEST_MARK) {} elseif ($s[strlen($s) - 2] == TERMINATOR) {} elseif ($s[1] == SKIP_MARK) {} else {
                    $sa = explode(':', $secondName);
                    if (isset($sa[1])) {
                        $this->_list[$i]['childName'] = $sa[1];
                    }

                    $depth++;
                    $this->_depths[$depth] = 1;
                    unset($parentId[$depth]);
                }
            }

            $result = $this->_parse($tag, $text, $cursor);
            $openElementPos = $result[0];
            $closeElementPos = $result[1];
            $properties = $result[2];
            $cursor = $openElementPos;

            $i++;
        }

        $this->_matchesByDepth = $this->sortMatchesByDepth();

        $this->_count = count($this->_list);

        return ($this->_count > 0);
    }

    public function sortMatchesByDepth(): array
    {
        $maxDepth = count($this->_depths);
        $result = [];
        for ($i = $maxDepth; $i > -1; $i--) {
            foreach ($this->_list as $part) {
                if ($part["depth"] == $i) {
                    $count = count($result);
                    $result[$count] = $part['id'];
                }
            }
        }

        return $result;
    }
}
