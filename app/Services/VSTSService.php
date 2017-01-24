<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 23/01/2017
 * Time: 18:02
 */

namespace App\Services;

use JWTAuth;
use GuzzleHttp\Client;


class VSTSService
{
    public function getAuthorizationURL( $user )
    {
        $url = 'https://app.vssps.visualstudio.com/oauth2/authorize';
        $parameters = http_build_query([

            'client_id' => env( 'VSTS_APP_ID' ),
            'response_type' => 'Assertion',
            'state' => $user -> userId,
            'scope' => env( 'VSTS_APP_SCOPE' ),
            'redirect_uri' => env( 'VSTS_REDIRECT_URL' )

        ]);

        return urldecode( $url . "?" . $parameters );
    }


    public function requestToken( $input )
    {
        $client = new Client( [ 'base_uri' => 'https://app.vssps.visualstudio.com/oauth2' ] );

        $client -> request( 'POST', 'token', [

            'query' => [
                'client_assertion_type' => 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer',
                'client_assertion' => env( 'VSTS_APP_SECRET' ),
                'grant_type' => 'ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $input[ "code" ],
                'redirect_uri' => 'https://innoflow.herokuapp.com/api/vsts/token/' . $input[ "state" ]
            ]

        ]);
    }


    public function refreshToken()
    {

    }
}