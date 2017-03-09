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


        $individualLevel = [];

        foreach ( $members as $member )
        {
            $individualLevel[] = [
                'username' => $member -> username,
                'id' => $member -> user_id,
                'contribution' => $member -> codeReviewMetric( $project )
            ];
        }

        $metrics[ 'codeReviewMetric' ] = [
            'totalValidCodeReviews' => $project -> codeReviewMetric(),
            'individualLevel' => $individualLevel
        ];



        $individualLevel = [];

        foreach ( $members as $member )
        {
            $individualLevel[] = [
                'username' => $member -> username,
                'id' => $member -> user_id,
                'contribution' => $member -> commitBalanceMetric( $project )
            ];
        }

        $metrics[ 'commitBalanceMetric' ] = [
            'averageCommitBalance' => $project -> commitBalanceMetric() ,
            'individualLevel' => $individualLevel
        ];



        $individualLevel = [];

        foreach ( $members as $member )
        {
            $individualLevel[] = [
                'username' => $member -> username,
                'id' => $member -> user_id,
                'contribution' => $member -> feedbackMetric( $project )
            ];
        }

        $metrics[ 'feedbackMetric' ] = [
            'totalFeedback' => $project -> feedbackMetric(),
            'individualLevel' => $individualLevel
        ];



        return $metrics;
    }
}
