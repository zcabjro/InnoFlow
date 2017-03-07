<?php
/**
 * Created by PhpStorm.
 * Member: andreas
 * Date: 24/01/2017
 * Time: 22:19
 */


namespace App\Http\Controllers;

use App\Http\Requests\Vsts\VstsAuthRequest;
use App\Repositories\User\UserRepoInterface;
use App\Services\Vsts\VstsApiService;


class WebController extends Controller
{
    public function getIndex()
    {
        return view( 'index' );
    }


    public function getAuthorizeIndex( VstsAuthRequest $request, VstsApiService $vstsService, UserRepoInterface $userRepo )
    {
        $user = $userRepo -> find( $request[ 'state' ] );
        $code = $request[ 'code' ];

        if ( !is_null( $user ) && !is_null( $code ) )
        {
            $vstsService -> storeToken( $user, $code );
        }

        return redirect() -> route( 'index', [ '#dashboard' ] ) -> with( [ 'waitForToken' => true ] );
    }
}
