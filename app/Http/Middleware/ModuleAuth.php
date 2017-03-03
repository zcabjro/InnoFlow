<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 03/03/2017
 * Time: 21:43
 */


namespace App\Http\Middleware;

use App\Services\Common\Helper;
use App\Traits\JsonResponseTrait;
use Closure;


class ModuleAuth
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
        $module = $request -> route() -> parameter( 'module' );
        $admins = $module -> admins;
        $user = Helper::currentUser();

        foreach ( $admins as $admin )
        {
            if ( $admin -> user_id == $user -> user_id )
            {
                return $next( $request );
            }
        }

        return $this -> respondUnauthorized( 'You are not an admin of this class.' );
    }
}