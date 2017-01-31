<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 24/01/2017
 * Time: 22:19
 */


namespace App\Http\Controllers\Web;

use App\Http\Requests\VSTS\VSTSAuthRequest;
use App\Services\VSTSService;
use App\Http\Controllers\Controller;


class HomeController extends Controller
{
    public function getIndex()
    {
        return view( 'index' );
    }


    public function getAuthorizeIndex( VSTSService $service, VSTSAuthRequest $request )
    {
        $service -> requestToken( $request -> all() );

        return redirect() -> route( 'index', [ '#dashboard' ] ) -> with( [ 'waitForToken' => true ] );
    }
}
