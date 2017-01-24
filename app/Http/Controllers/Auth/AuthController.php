<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 18/01/2017
 * Time: 11:42
 */

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Repositories\User\UserRepo;
use App\Traits\JsonResponseTrait;
use App\Http\Controllers\Controller;


class AuthController extends Controller
{
    use JsonResponseTrait;


    protected $userRepo;


    public function __construct( UserRepo $userRepo )
    {
        $this -> userRepo = $userRepo;
    }


    public function loginUser( LoginRequest $request )
    {
        $token = $this -> userRepo -> login( $request -> all() );

        if ( !$token )
        {
            return $this -> respondUnauthorized( "Login details are incorrect." );
        }

        return response() -> json() -> cookie( 'token', $token, config( 'cookie.ttl' ) );
    }


    public function logoutUser()
    {
        $this -> userRepo -> logout();
    }


    public function registerUser( RegisterRequest $request )
    {
        $this -> userRepo -> create( $request -> all() );
    }
}