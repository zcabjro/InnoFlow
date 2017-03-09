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


    public function login( $data )
    {
        return JWTAuth::attempt( $data );
    }


    public function create( array $data )
    {
        return $this -> model -> create( $data );
    }


    public function find( $id )
    {
        return $this -> model -> find( $id );
    }


    public function findBy( $attribute, $value )
    {
        return $this -> model -> where( $attribute, $value ) -> first();
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