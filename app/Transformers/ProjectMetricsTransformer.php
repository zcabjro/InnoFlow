<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 07/03/2017
 * Time: 19:33
 */


namespace App\Transformers;

use App\Models\VstsProject;
use League\Fractal\TransformerAbstract;


class ProjectMetricsTransformer extends TransformerAbstract
{
    public function transform( VstsProject $project )
    {
        $members = $project -> members;
        $metrics = [];


        $individual = [];

        foreach ( $members as $member )
        {
            $individual[] = [
                'username' => $member -> username,
                'id' => $member -> user_id,
                'contribution' => $member -> codeReviewMetric( $project )
            ];
        }

        $metrics[ 'codeReviewMetric' ] = [
            'totalValidCodeReviews' => $project -> codeReviewMetric(),
            'individual' => $individual
        ];



        $individual = [];

        foreach ( $members as $member )
        {
            $individual[] = [
                'username' => $member -> username,
                'id' => $member -> user_id,
                'contribution' => $member -> commitBalanceMetric( $project )
            ];
        }

        $metrics[ 'commitBalanceMetric' ] = [
            'averageCommitBalance' => $project -> commitBalanceMetric() ,
            'individual' => $individual
        ];



        $individual = [];

        foreach ( $members as $member )
        {
            $individual[] = [
                'username' => $member -> username,
                'id' => $member -> user_id,
                'contribution' => $member -> feedbackMetric( $project )
            ];
        }

        $metrics[ 'feedbackMetric' ] = [
            'totalFeedback' => $project -> feedbackMetric(),
            'individual' => $individual
        ];



        return $metrics;
    }
}
