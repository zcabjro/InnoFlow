<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 07/03/2017
 * Time: 22:11
 */


namespace App\Http\Controllers;

use App\Services\VSTS\VstsApiService;
use Illuminate\Http\Request;

class CommitController extends Controller
{
    public function store( Request $request, VstsApiService $vstsApiService )
    {
        $vstsApiService -> storeCommit( $request );
    }
}