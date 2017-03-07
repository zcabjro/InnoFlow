<?php
/**
 * Created by PhpStorm.
 * Member: andreas
 * Date: 02/03/2017
 * Time: 18:18
 */

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\RefreshProjectRequest;
use App\Models\Commit;
use App\Models\VstsProject;
use App\Services\Common\Helper;
use App\Services\Vsts\VstsApiService;
use App\Traits\JsonResponseTrait;
use App\Transformers\CommitTransformer;
use App\Transformers\ProjectTransformer;
use GuzzleHttp\Exception\ClientException;


class CommitController extends Controller
{
    use JsonResponseTrait;


    public function index( VstsProject $vstsProject, RefreshProjectRequest $request, VstsApiService $vstsApiService )
    {
        $user = Helper::currentUser();

        try
        {
            $vstsApiService -> storeCommits( $user, $vstsProject, $request -> refresh );
        }
        catch( ClientException $e )
        {
            $user -> resetVsts();
            return $this -> respondUnauthorized( 'Token cannot be refresh. Authorize with Vsts again.' );
        }

        return fractal() -> parseIncludes( [ 'commits' ] ) -> item( $vstsProject, new ProjectTransformer );
    }


    public function show( VstsProject $vstsProject, Commit $commit )
    {
        return fractal() -> item( $commit, new CommitTransformer );
    }
}