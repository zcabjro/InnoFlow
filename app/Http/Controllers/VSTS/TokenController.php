<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 17/01/2017
 * Time: 10:40
 */


namespace App\Http\Controllers\VSTS;

use App\Http\Requests\VSTS\VSTSTokenRequest;
use App\Models\Test;
use App\Repositories\User\UserRepo;
use App\Http\Controllers\Controller;


class TokenController extends Controller
{
    private $userRepo;


    public function __construct( UserRepo $userRepo )
    {
        $this -> userRepo = $userRepo;
    }


    public function store( VSTSTokenRequest $request, $id )
    {
        $updates = [ 'vsts_token' => $request -> get( 'access_token' ), 'vsts_refresh_token' => $request -> get( 'access_token' ) ];
        $this -> userRepo -> update( $id, $updates );
    }

    public function test()
    {
        $test = new Test();
        $test -> save();
    }
}