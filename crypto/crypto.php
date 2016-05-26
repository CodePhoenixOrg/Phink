<?php

namespace Phink\Crypto;

/**
 * Description of crypto
 *
 * @author david
 */
class TCrypto
{
	//put your code here

    public static function generateToken($key = '')
    {
        if($key != '') {
            $token = uniqid($key, true);
        } else {
            $token = uniqid(rand());
        }
            
        $token = base64_encode($token);
        
        return $token;
    }
}
