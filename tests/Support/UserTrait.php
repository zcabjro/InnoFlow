<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 13/02/2017
 * Time: 23:39
 */

namespace Test\Support;

use App\Models\User;


trait UserTrait
{
    /**
     * Creates a user in the database.
     *
     * @param array $data
     */
    public function createUser( array $data )
    {
        $token = array_key_exists( "vsts_access_token", $data ) ? $data[ "vsts_access_token" ] : null;

        $user = new User();
        $user -> email = $data[ "email" ];
        $user -> password = bcrypt( $data[ "password" ] );
        $user -> vsts_access_token = $token;
        $user -> save();
    }
}