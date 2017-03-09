<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 08/03/2017
 * Time: 21:27
 */

namespace App\Transformers;

use App\Models\Module;
use League\Fractal\TransformerAbstract;


class ModuleMetricsTransformer extends TransformerAbstract
{
    public function transform( Module $module )
    {
        $projects = $module -> projects;
        $metrics = [];


        $projectLevel = [];

        foreach ( $projects as $project )
        {
            $projectLevel[] = [
                'id' => $project -> project_id,
                'name' => $project -> name,
                'contribution' => $project -> codeReviewMetric()
            ];
        }

        $metrics[ 'codeReviewMetric' ] = [
            'averageValidCodeReviews' => $module -> codeReviewMetric(),
            'projectLevel' => $projectLevel
        ];



        $projectLevel = [];

        foreach ( $projects as $project )
        {
            $projectLevel[] = [
                'id' => $project -> project_id,
                'name' => $project -> name,
                'contribution' => $project -> commitBalanceMetric()
            ];
        }

        $metrics[ 'commitBalanceMetric' ] = [
            'averageCommitBalance' => $module -> commitBalanceMetric() ,
            'projectLevel' => $projectLevel
        ];



        $projectLevel = [];

        foreach ( $projects as $project )
        {
            $projectLevel[] = [
                'id' => $project -> project_id,
                'name' => $project -> name,
                'contribution' => $project -> feedbackMetric()
            ];
        }

        $metrics[ 'feedbackMetric' ] = [
            'totalFeedback' => $module -> feedbackMetric(),
            'projectLevel' => $projectLevel
        ];



        return $metrics;
    }
}
