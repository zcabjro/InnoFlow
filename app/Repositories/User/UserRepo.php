<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 23/01/2017
 * Time: 14:35
 */

namespace App\Repositories\User;

use App\Repositories\User\UserRepoInterface;
use Illuminate\Support\Facades\Input;
use App\Models\User;
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


    public function logout()
    {
        JWTAuth::getToken() -> invalidate();
    }


    public function loggedIn()
    {
        return JWTAuth::toUser( JWTAuth::getToken() );
    }


    public function create( array $input )
    {
        $this -> model -> create([
            'email' => $input[ "email" ],
            'password' => bcrypt( $input[ "password" ] ),
        ]);
    }


    public function update( $id, array $data )
    {
        $this -> model -> where( $this -> model -> getKeyName(), '=', $id ) -> update( $data );
    }
}