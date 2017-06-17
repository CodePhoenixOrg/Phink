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
