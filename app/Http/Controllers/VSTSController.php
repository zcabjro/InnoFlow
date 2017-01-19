<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 17/01/2017
 * Time: 10:40
 */


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\User;


class VSTSController extends Controller
{
    public function login( Request $request )
    {

    }


    public function authorizeApp( Request $request )
    {
        $client = new Client( [ 'base_uri' => 'https://app.vssps.visualstudio.com/oauth2' ] );

        $client -> request( 'GET', 'authorize', [

            'query' => [
                'client_id' => config( 'custom.vsts.clientId' ),
                'response_type' => 'Assertion',
                'state' => 'User1',
                'scope' => config( 'custom.vsts.scope' ),
                'redirect_uri' => config( 'custom.vsts.redirectUri' )
            ]

        ]);
    }

    public function authorizeAppCallback( Request $request )
    {
        $user = new User();
        $user -> save();
    }
}