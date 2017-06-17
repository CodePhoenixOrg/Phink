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
* Description of TStringUtils
*
* @author david
*/
class TStringUtils
{

    public static function stringToArray($string = '', $delimiter1 = ' ', $delimiter2 = '=')
    {

        (array)$result = NULL;

        (array)$exploded = explode($delimiter1, $string);

        if($delimiter2 != '') {
            $c = count($exploded);
            for($i = 0; $i < $c; $i++) {
                (array)$keyValue = explode($delimiter2, $exploded[$i]);
                $result[$keyValue[0]] = $keyValue[1];
            }
        }
        else {
            $result = $exploded;
        }

        return $result;
    }

    public static function stringToDictionary($string = '')
    {
        (array)$result = NULL;

        $result = preg_split('/([-:a-z0-9]+=["a-z0-9-_]+)/i', $string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE);

        $c = count($result);

        for($i = 0; $i < $c; $i++) {
            (array)$keyValue = explode('=', $result[$i][0]);
            $keyValue[1] = str_replace('"', '', $keyValue[1]);

            $result[$keyValue[0]] = $keyValue[1];
        }
        return $result;
    }

    public static function parameterStringToArray($string)
    {
        (array)$result = NULL;

        $string = str_replace('\"', '"', $string);
        $string = str_replace(' ', '�', $string);
        $string = str_replace('"�', '" ', $string);
        $string = str_replace('"', '', $string);

        $result = TStringUtils::stringToArray($string, ' ', '=');

        foreach($result as $key=>$value) {
            $result[$key] = str_replace('�', ' ', $value);
        }

        return $result;
    }

    public static function elementType($element)
    {
        $result = '';

        $parts = explode(' ', $element);
        $result = substr($parts[0], 1);

        return $result;

    }

    public static function phraseCase($string)
    {
        $upper = strtoupper($string[0]);

        return ($upper == '') ? $string : $upper . substr($string, 1);
    }

}
