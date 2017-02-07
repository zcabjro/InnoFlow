<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 23/01/2017
 * Time: 14:35
 */

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use JWTAuth;



class UserRepo implements UserRepoInterface
{
    private $model;


    public function __construct( User $model )
    {
        $this -> model = $model;
    }


    public function login( $input )
    {
        $credentials = [ "email" => $input[ "email" ], "password" => $input[ "password" ] ];
        return JWTAuth::attempt( $credentials );
    }


    public function create( array $input )
    {
        return $this -> model -> create([
            'email' => $input[ "email" ],
            'password' => bcrypt( $input[ "password" ] ),
        ]);
    }


    public function update( $id, array $data )
    {
        $this -> model -> where( $this -> model -> getKeyName(), '=', $id ) -> update( $data );
    }


    public function findByCredentials( $email, $password )
    {
        if ( is_null( $user = $this -> model-> where( 'email', $email ) -> first() ) )
        {
            return null;
        }

        if ( !Hash::check( $password, $user -> password ) )
        {
            return null;
        }

        return $user;
    }
}