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
 
 namespace Phink\Utils {

/**
* Description of asqlutils
*
* @author david
*/
class TSqlUtils
{
        //put your code here
        public static function firstFieldFromSelectClause($sql)
    {
            $select="select";
            $from="from";

            $result= '';

            $l=strlen($select)+1;
            $p=stripos($sql, $from);
            $fields=substr($sql, $l, $p-$l);

            $fields=explode(",", $fields);
            $result = trim($fields[0]);

            return $result;
        }

        public static function allFieldsFromSelectClause($sql)
    {
            $select="select";
            $from="from";

            $result=array();

            $l=strlen($select)+1;
            $p=stripos($sql, $from);
            $fields=substr($sql, $l, $p-$l);

            $fields=explode(",", $fields);
            $i=0;
            foreach($fields as $field) {
                $afields=explode(" ", trim($field));
                $s=sizeof($afields)-1;
                $result[$i]=trim($afields[0]);
                $i++;
            }

            return $result;
        }


    }
}
