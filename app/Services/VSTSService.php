<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 23/01/2017
 * Time: 18:02
 */

namespace App\Services;
;
use App\Models\Test;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use App\Repositories\User\UserRepo;


class VSTSService
{
    private $userRepo;
    private $client;


    public function __construct( UserRepo $userRepo )
    {
        $this -> userRepo = $userRepo;
        $this -> client = new Client();
    }


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
        try
        {
            $response = $this -> client -> request( 'POST', 'https://app.vssps.visualstudio.com/oauth2/token', [

                'form_params' => [
                    'client_assertion_type' => 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer',
                    'client_assertion' => env( 'VSTS_APP_SECRET' ),
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $input[ 'code' ],
                    'redirect_uri' => env( 'VSTS_REDIRECT_URL' )
                ]

            ]);

            $test = new Test();
            $json = json_decode( $response -> getBody(), true );
            $test -> token( dd( $json ) );
            $test -> save();
            dd( $json );
            $updates = [ 'vsts_access_token' => $json[ 'access_token' ], 'vsts_refresh_token' => $json[ 'refresh_token' ] ];
            $test -> response( $updates );
            $test -> save();
            //dd( $updates );
            //dd( User::find( $input[ 'state' ] ) );
            $this -> userRepo -> update( $input[ 'state' ], $updates );
            dd( $updates );
            dd( User::find( $input[ 'state' ] ) );
        }
        catch ( ClientException $e )
        {
            // Error occured
        }
    }


    public function refreshToken()
    {

    }
}