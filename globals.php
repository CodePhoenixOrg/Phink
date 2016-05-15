<?php

//function value($array, $key)
//    {
//    $result = false;
//
//    
//    if(array_key_exists($key, $array)) {
//        $result = $array[$key];
//    }
//    
//    return $result;
//}

// For 4.3.0 <= PHP <= 5.4.0
if (!function_exists('http_response_code'))
{
    function http_response_code($newcode = NULL)
    {
        static $code = 200;
        if($newcode !== NULL)
        {
            header('X-PHP-Response-Code: '.$newcode, true, $newcode);
            if(!headers_sent())
                $code = $newcode;
        }       
        return $code;
    }
}