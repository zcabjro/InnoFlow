<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 18/01/2017
 * Time: 11:42
 */

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

use App\Transformers\UserTransformer;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Traits\JsonResponseTrait;


class AuthController extends Controller
{
    use JsonResponseTrait;

    public function loginUser( LoginRequest $request )
    {
        // Grab credentials from the request
        $credentials = $request -> only( 'email', 'password' );

        // Attempt to verify the credentials and create a token for the user
        try
        {
            if ( ! $token = JWTAuth::attempt( $credentials ) )
            {
                return $this -> respondUnauthorized( "Login details are incorrect." );
            }
        }
        // Something went wrong whilst attempting to encode the token
        catch ( JWTException $e )
        {
            return $this -> respondInternalError();
        }

        $user = fractal() -> item( JWTAuth::toUser( $token ), new UserTransformer );

        return response() -> json( $user ) -> cookie( 'token', $token, 60 );
    }

    public function registerUser( RegisterRequest $request )
    {
        User::create([
            'email' => $request -> email,
            'password' => bcrypt( $request -> password ),
        ]);
    }
}