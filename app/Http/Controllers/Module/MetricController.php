<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 08/03/2017
 * Time: 21:26
 */


namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Transformers\ModuleMetricsTransformer;


class MetricController extends Controller
{
    public function index( Module $module )
    {
        return fractal() -> item( $module, new ModuleMetricsTransformer );
    }
}