<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 01/02/2017
 * Time: 23:27
 */


namespace App\Http\Controllers;

use App\Http\Requests\User\UserSearchRequest;
use App\Models\User;
use App\Transformers\UserTransformer;
use Searchy;


class UserController extends Controller
{
    public function search( UserSearchRequest $request )
    {
        $users = User::hydrate( Searchy::users( [ 'email', 'username' ] ) -> query( $request[ 'string' ] ) -> getQuery() -> limit( 10 ) -> get() -> toArray() );
        return fractal() -> collection( $users, new UserTransformer );
    }
}