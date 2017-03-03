<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 02/03/2017
 * Time: 18:18
 */

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Commit;
use App\Models\VstsProject;
use App\Services\Common\Helper;
use App\Services\Vsts\VstsApiService;
use App\Transformers\CommitTransformer;
use App\Transformers\VstsProjectTransformer;


class CommitController extends Controller
{
    public function index( VstsProject $vstsProject )
    {
        return fractal() -> parseIncludes( [ 'commits' ] ) -> item( $vstsProject, new VstsProjectTransformer );
    }


    public function show( VstsProject $vstsProject, Commit $commit, VstsApiService $vstsApiService )
    {
        $user = Helper::currentUser();
        $commit = $vstsApiService -> completeCommit( $user, $commit );

        return fractal() -> item( $commit, new CommitTransformer );
    }
}