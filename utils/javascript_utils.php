<?php
namespace Origin\Utils;

/**
 * Description of miscUtils
 *
 * @author david
 */


class AJavascriptUtils
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
