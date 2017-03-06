<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 06/03/2017
 * Time: 11:42
 */


namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\VstsProject;


class MetricController extends Controller
{
    public function index( VstsProject $vstsProject )
    {
        /*$totalValidReviews = 0;
        $totalInvalidReviews = 0;

        $users = $vstsProject -> users;

        foreach ( $vstsProject -> codeReviews as $codeReview )
        {
            if ( $codeReview -> commits() -> count() == 0 )
            {
                $totalInvalidReviews++;;
                continue;
            }

            $totalValidReviews++;

        }*/

    }
}