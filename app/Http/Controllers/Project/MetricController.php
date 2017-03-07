<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 07/03/2017
 * Time: 12:32
 */


namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\VstsProject;
use App\Transformers\ProjectMetricsTransformer;


class MetricController extends Controller
{
    public function index( VstsProject $vstsProject )
    {
        return fractal() -> item( $vstsProject, new ProjectMetricsTransformer );
    }
}
