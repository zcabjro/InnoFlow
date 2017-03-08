<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 07/03/2017
 * Time: 23:17
 */


namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Transformers\InnovationTransformer;


class InnovationController extends Controller
{
    public function index( User $user )
    {
        $innovations = $user -> innovations;
        return fractal() -> collection( $innovations, new InnovationTransformer );
    }
}