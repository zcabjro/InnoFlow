<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 03/03/2017
 * Time: 21:26
 */


namespace App\Http\Middleware;

use App\Services\Common\Helper;
use App\Traits\JsonResponseTrait;
use Closure;


class ProjectAuth
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
        $vstsProject = $request -> route() -> parameter( 'vstsProject' );
        $members = $vstsProject -> account -> users;
        $user = Helper::currentUser();

        foreach ( $members as $member )
        {
            if ( $member -> user_id == $user -> user_id )
            {
                return $next( $request );
            }
        }

        return $this -> respondUnauthorized( 'You are not a member of this project.' );
    }
}
