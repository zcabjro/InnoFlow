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
use Illuminate\Support\Facades\DB;


class MetricController extends Controller
{
    public function index( VstsProject $vstsProject )
    {
        /*$totalValidReviews = 0;
        $codeReviews = $vstsProject -> codeReviews;
        $users = $vstsProject -> users;

        foreach ( $codeReviews as $codeReview )
        {
            if ( !$codeReview -> is_active )
            {
                continue;
            }

            $totalValidReviews++;

        }

        return [ $totalValidReviews, count( $codeReviews ) ];

        $users = DB::table( 'users' )
            -> join( 'vsts_project_users', 'users.user_id', '=', 'vsts_project_users.user_id' )
            -> join( 'vsts_projects', 'vsts_project_users.project_id', '=', 'vsts_projects.project_id' )
            -> join( 'code_reviews', 'vsts_projects.project_id', '=', 'code_reviews.project_id' )
            -> select( DB::raw( 'users.user_id, code_reviews.code_review_id, count(*) as counter' ) )
            -> where( 'vsts_project_users.project_id', $vstsProject -> project_id )
            -> where( 'code_reviews.is_active', true )
            -> groupBy( 'users.user_id', 'code_reviews.is_active' )
            -> get();

        return $users;*/



    }
}