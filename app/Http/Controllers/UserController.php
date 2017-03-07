<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 07/03/2017
 * Time: 23:07
 */

namespace App\Http\Controllers;

use App\Http\Requests\Module\SearchRequest;
use App\Models\User;
use App\Transformers\LightUserTransformer;


class UserController extends Controller
{
    public function search( SearchRequest $request )
    {
        $users = User::where( 'username', 'like', '%' . $request -> string . '%' ) -> get();
        return fractal() -> collection( $users, new LightUserTransformer );
    }
}