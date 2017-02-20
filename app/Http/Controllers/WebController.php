<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 24/01/2017
 * Time: 22:19
 */


namespace App\Http\Controllers;

use App\Http\Requests\VSTS\VSTSAuthRequest;
use App\Repositories\User\UserRepoInterface;
use App\Services\VSTS\VstsApiService;


class WebController extends Controller
{
    public function getIndex()
    {
        return view( 'index' );
    }


    public function getAuthorizeIndex( VSTSAuthRequest $request, VstsApiService $vstsService, UserRepoInterface $userRepo )
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
