<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 11/01/2017
 * Time: 12:12
 */

namespace App\Http\Controllers;

use App\Services\Vsts\VstsApiService;
use Illuminate\Http\Request;


class CommitController extends Controller
{
    public function store( Request $request, VstsApiService $vstsApiService )
    {
        $vstsApiService -> storeCommit( $request );
    }
}