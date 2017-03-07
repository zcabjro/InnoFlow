<?php

namespace App\Http\Middleware;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

use App\Traits\JsonResponseTrait;

use JWTAuth;
use Closure;



class JWTTokenAuth
{
    use JsonResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle( $request, Closure $next )
    {
        $newToken = null;

        try
        {
            // Get token from http only cookie
            $token = $request -> cookie( config( 'custom.cookie.name' ) );
            JWTAuth::setToken( $token );

            // Member cannot be found
            if ( is_null( JWTAuth::toUser( $token ) ) )
            {
                return $this -> respondNotFound( "Member not found" );
            }
        }
        // Token expired
        catch ( TokenExpiredException $e )
        {
            // Refresh token
            try
            {
                $newToken = JWTAuth::refresh( $token );
                JWTAuth::setToken( $newToken );
            }
            // Token is blacklisted (login again)
            catch ( TokenBlacklistedException $e )
            {
                return $this -> respondUnauthorized( "Token was invalidated. Login again." );
            }
            // Token refresh ttl time is expired as well (login again)
            catch ( TokenExpiredException $e )
            {
                return $this -> respondUnauthorized( "Token expired. Login again." );
            }
        }
        // Token does not exist anymore
        catch ( TokenInvalidException $e )
        {
            return $this -> respondNotFound( "Token does not exist anymore. Login again." );
        }
        // No token attached to request
        catch ( JWTException $e )
        {
            return $this -> respondBadRequest( "Token is absent." );
        }

        // After middleware
        $response = $next( $request );

        // Token was refreshed, hence update http only cookie
        if ( !is_null( $newToken ) )
        {
            $response -> cookie( config( 'custom.cookie.name' ), $newToken, config( 'custom.cookie.ttl' ) );
        }

        return $response;
    }
}