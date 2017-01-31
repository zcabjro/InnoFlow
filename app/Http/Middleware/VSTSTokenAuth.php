<?php

namespace App\Http\Middleware;

use App\Services\VSTSService;
use App\Traits\JsonResponseTrait;
use Closure;
use JWTAuth;


class VSTSTokenAuth
{
    use JsonResponseTrait;

    private $vstsService;


    public function __construct( VSTSService $vstsService )
    {
        $this -> vstsService = $vstsService;
    }


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle( $request, Closure $next )
    {
        $user = JWTAuth::toUser( JWTAuth::getToken() );

        if ( is_null( $user -> vsts_access_token ) )
        {
            $url = $this -> vstsService -> getAuthorizationURL( $user );

            return $this -> respondForbidden( [
                'message' => 'User has not authorized the app.',
                'url' => $url
            ]);
        }

        return $next( $request );
    }
}
