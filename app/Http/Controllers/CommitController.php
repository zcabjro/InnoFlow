<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 07/03/2017
 * Time: 22:11
 */


namespace App\Http\Controllers;

use App\Events\CommitCreatedEvent;
use App\Services\Vsts\VstsApiService;
use Illuminate\Http\Request;

class CommitController extends Controller
{
    public function store( Request $request, VstsApiService $vstsApiService )
    {
        $vstsProject = $vstsApiService -> storeCommit( $request );

        if ( !is_null( $vstsProject ) )
        {
            event( new CommitCreatedEvent( $vstsProject ) );
        }
    }
}