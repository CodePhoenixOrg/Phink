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
 
 namespace Phink\Utils;

/**
 * Description of miscUtils
 *
 * @author david
 */


class TJavascriptUtils
{


    public static function jsonEncode($string)
    {
        return json_encode($string, JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);
    }
    
    // Découpe une phrase en plusieurs morceaux et en déplace un aléatoirement
    // Cette fonction est principalement utilisée pour brouiller les urls.
    // Elle a un pendant côté JavaScript pour reformer la phrase (ou l'url) $.jApoBox.unscramble()
    public static function scramble($phrase)
    {
        $result = '';

        $sl = array();

        $i = 0;
        $l = 0;
        $phrasel = strlen($phrase);
        while($i + 1 < $phrasel)
        {
            $l = rand(1, 6);
            if(($i + $l + 1) < $phrasel)
            {
                $sl[] = substr($phrase, $i, $l);	
            }
            else
            {
                $sl[] = substr($phrase, $i);
            }
            $i += $l;
        }

        $slc = count($sl);
        $translatorPos = rand(0, $slc);
        $groupPos = rand(0, $slc);

        $grp = $sl[$translatorPos];
        unset($sl[$translatorPos]);

        array_splice($sl,$translatorPos,0,"§" . $groupPos);
        array_splice($sl,$groupPos,0,$grp);

        $result = implode("-", $sl);


        return $result;
    }    
    

}

// Raccourcis de JSUtils::jsonEncode();
function jsonEncode($string)
    {
    return AJavascriptUtils::jsonEncode($string);
}

?>
