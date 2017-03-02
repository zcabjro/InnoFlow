<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 02/03/2017
 * Time: 18:18
 */

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\VstsProject;
use App\Transformers\VstsProjectTransformer;


class CommitController extends Controller
{
    public function index( VstsProject $vstsProject )
    {
        return fractal() -> parseIncludes( [ 'commits' ] ) -> item( $vstsProject, new VstsProjectTransformer );
    }
}