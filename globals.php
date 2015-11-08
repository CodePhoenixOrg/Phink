<?php

function value($array, $key)
    {
    $result = false;

    
    if(array_key_exists($key, $array)) {
        $result = $array[$key];
    }
    
    return $result;
}
