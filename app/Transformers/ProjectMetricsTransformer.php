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
        $fields = [];

        $fields[ 'projectMetrics' ] = [
            'activeCodeReviews' => $project -> activeCodeReviewMetric(),
            'codeReviews' => $project -> codeReviewMetric(),
            'commits' => $project -> commitMetric(),
            'feedback' => $project -> feedbackMetric(),
        ];

        $membersMetrics = [];
        foreach ( $project -> members as $member )
        {
            $membersMetrics[] = [
                'user' => [
                    'id' => $member -> user_id,
                    'username' => $member -> username
                ],
                'activeCodeReviews' => $member -> activeCodeReviewMetric( $project ),
                'codeReviews' => $member -> codeReviewMetric( $project ),
                'commits' => $member -> commitMetric( $project ),
                'feedback' => $member -> feedbackMetric( $project ),
            ];
        }
        $fields[ 'membersMetrics' ] = $membersMetrics;

        return $fields;
    }
}
