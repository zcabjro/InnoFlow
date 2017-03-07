<?php

namespace App\Http\Middleware;

use App\Services\VSTS\VstsApiService;
use App\Services\Common\Helper;
use App\Traits\JsonResponseTrait;
use Closure;
use JWTAuth;


class VSTSTokenAuth
{
    use JsonResponseTrait;

    private $vstsService;


    public function __construct( VstsApiService $vstsService )
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
        $user = Helper::currentUser();

        if ( is_null( $user ) || is_null( $user -> vsts_access_token ) )
        {
            $url = $this -> vstsService -> getAuthorizationURL( $user );

            return $this -> respondForbidden( [
                'message' => 'Member has not authorized the app.',
                'url' => $url
            ]);
        }

        return $next( $request );
    }
}
