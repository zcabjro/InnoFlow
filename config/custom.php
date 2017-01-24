<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 17/01/2017
 * Time: 11:32
 */

return [

    'vsts' => [
        'clientId' => env( 'VSTS_APP_ID' ),
        'scopes' => 'vso.code vso.project_manage',
        'redirectUri' => 'https://innoflow.herokuapp.com/api/authorize-callback'
    ],

];