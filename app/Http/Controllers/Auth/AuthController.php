<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 18/01/2017
 * Time: 11:42
 */

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\User\UserRepo;
use App\Traits\JsonResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;


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
        return response() -> json() -> withCookie( Cookie::forget( 'token' ) );
    }


    public function registerUser( RegisterRequest $request )
    {
        $this -> userRepo -> create( $request -> all() );
    }


    public function isAuthorized()
    {
        $user = $this -> userRepo -> loggedIn();
        $authorized = !is_null( $user -> vsts_access_token );
        return response() -> json( [ 'authorized' => $authorized ] );
    }
}