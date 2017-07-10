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
 * Description of textUtils
 *
 * @author david
 */
class TTextUtils
{
    //put your code here

    public static function escapeQuotes($phrase)
    {
        $result = '';

        $result = str_replace("'", "\'", $phrase);

        return $result;
    }

    public static function escapeSqlQuotes($phrase)
    {
        $result = '';

        $result = str_replace("'", "''", $phrase);

        return $result;
    }

    public static function escapeHtmlQuotes($phrase)
    {
        $result = '';

        $result = str_replace("'", "&quot;", $phrase);

        return $result;
    }

    public static function removeAccents($string)
    {
        return str_replace( array('à','á','â','ã','ä','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ù','ú','û','ü','ý','ÿ','À','Á','Â','Ã','Ä','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ñ','Ò','Ó','Ô','Õ','Ö','Ù','Ú','Û','Ü','Ý'), array('a','a','a','a','a','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','u','u','u','u','y','y','A','A','A','A','A','C','E','E','E','E','I','I','I','I','N','O','O','O','O','O','U','U','U','U','Y'), $string);
    }
    

    public static function string2db($string)
    {
        if ($string == '0') return '';
        return htmlentities(TTextUtils::escapeSqlQuotes($string));
    }

    public static function htmlSpace($string)
    {
        $result = '';

        if ($string) {
            $result = str_replace(' ', '&nbsp;', $string);
        }

        return $result;
    }    
    
    public static function ConvertNumTel($tel)
    {
        return $tel;
    }

    public static function telspace($tel)
    {
        $tel = str_replace(" ","",$tel);
        $tel = str_replace(".","",$tel);
        $tel = str_replace("-","",$tel);
        $tel = substr($tel,0,2)." ".substr($tel,2,2)." ".substr($tel,4,2)." ".substr($tel,6,2)." ".substr($tel,8,2);
        return $tel;
    }    
    
    public static function numberformat($number,$dec)
    {
        $result = number_format($number, $dec, ',', ' ');
        $result = str_replace(' ', '&nbsp;', $result);

        return $result;
    }    
}
