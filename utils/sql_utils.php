<?php
namespace Phoenix\Utils {

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
