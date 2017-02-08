<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 01/02/2017
 * Time: 21:24
 */

namespace App\Services;

use JWTAuth;

class Helper
{
    public static function currentUser()
    {
        return JWTAuth::toUser( JWTAuth::getToken() );
    }

    public static function uniqueRandomNumbersWithinRange( $min, $max, $quantity )
    {
        $numbers = range( $min, $max );
        shuffle( $numbers );
        return array_slice( $numbers, 0, $quantity );
    }
}

