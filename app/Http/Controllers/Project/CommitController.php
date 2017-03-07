<?php
/**
 * Created by PhpStorm.
 * Member: andreas
 * Date: 02/03/2017
 * Time: 18:18
 */

namespace App\Http\Controllers\Project;


use App\Http\Controllers\Controller;
use App\Models\Commit;
use App\Models\VstsProject;
use App\Transformers\CommitTransformer;
use App\Transformers\ProjectTransformer;


class CommitController extends Controller
{
    public function index( VstsProject $vstsProject )
    {
        return fractal() -> parseIncludes( [ 'commits' ] ) -> item( $vstsProject, new ProjectTransformer );
    }


    public function show( VstsProject $vstsProject, Commit $commit )
    {
        return fractal() -> item( $commit, new CommitTransformer );
    }
}