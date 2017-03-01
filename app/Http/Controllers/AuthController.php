<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 18/01/2017
 * Time: 11:42
 */

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\User\UserRepoInterface;
use App\Services\VSTS\VstsApiService;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\Cookie;
use App\Services\Common\Helper;
use JWTAuth;


class AuthController extends Controller
{
    use JsonResponseTrait;


    private $userRepo;


    public function __construct( UserRepoInterface $userRepo )
    {
        $this -> userRepo = $userRepo;
    }


    public function loginUser( LoginRequest $request )
    {
        $token = $this -> userRepo -> login( [
            'email' => $request[ 'email' ],
            'password' => $request[ 'password' ]
        ] );

        if ( !$token )
        {
            return $this -> respondUnauthorized( 'Login details are incorrect.' );
        }

        return response() -> json() -> cookie( config( 'custom.cookie.name' ), $token, config( 'custom.cookie.ttl' ) );
    }


    public function logoutUser()
    {
        JWTAuth::invalidate( JWTAuth::getToken() );
        return response() -> json() -> withCookie( Cookie::forget( config( 'custom.cookie.name' ) ) );
    }


    public function registerUser( RegisterRequest $request )
    {
        $this -> userRepo -> create( [
            'email' => $request[ 'email' ],
            'password' => bcrypt( $request[ 'password' ] ),
        ]);
    }


    public function isLoggedIn()
    {
        $isLoggedIn = !is_null( Helper::currentUser() );
        return response() -> json( [ 'isLoggedIn' => $isLoggedIn ] );
    }


    public function isVstsAuthorized( VstsApiService $vstsService )
    {
        $user = Helper::currentUser();
        $response = [];

        $authorized = !is_null( $user -> vsts_access_token );
        $response[ 'isAuthorized' ] = $authorized;

        if ( !$authorized )
        {
            $response[ 'url' ] = $vstsService -> getAuthorizationURL( $user );
        }

        return response() -> json( $response );
    }
}