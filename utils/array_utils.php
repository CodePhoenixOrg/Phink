<?php

namespace Origin\Utils {
    /**
    * Description of astringutils
    *
    * @author david
    */
    class AArrayUtils
{
        //put your code here

        public static function assocArrayByAttribute(array $array, $attribute)
    {
            $result = array();

            $c = count($array);
            for ($i = 0; $i < $c; $i++) {
                $method = 'get' . AStringUtils::phraseCase($attribute);
                $object = $array[$i];
                $intermediate = $object->$method();
                $result[$intermediate] = $object;
            }

            return $result;
        }
    }
}
?>
