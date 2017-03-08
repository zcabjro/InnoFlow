<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 08/03/2017
 * Time: 12:52
 */


namespace App\Listeners;

use App\Events\CodeReviewActiveEvent;
use App\Events\CommentCreatedEvent;
use App\Events\CommitCreatedEvent;
use Illuminate\Events\Dispatcher;


class ProjectMetricSubscriber
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher  $events
     */
    public function subscribe( $events )
    {
        $events -> listen(
            'App\Events\CodeReviewActiveEvent',
            'App\Listeners\ProjectMetricSubscriber@codeReviewCreated'
        );

        $events -> listen(
            'App\Events\CommitCreatedEvent',
            'App\Listeners\ProjectMetricSubscriber@commitCreated'
        );

        $events -> listen(
            'App\Events\CommentCreatedEvent',
            'App\Listeners\ProjectMetricSubscriber@commentCreated'
        );
    }


    public function commitCreated( CommitCreatedEvent $event )
    {
        $vstsProject = $event -> vstsProject;

        $commitMetric = $vstsProject -> commit_metric;
        $vstsProject -> commit_metric = ++$commitMetric;
        $vstsProject -> save();
    }


    public function codeReviewCreated( CodeReviewActiveEvent $event )
    {
        $codeReview =  $event -> codeReview;
        $vstsProject = $event -> vstsProject;

        if ( $codeReview -> is_active )
        {
            return;
        }

        $codeReview -> is_active = true;
        $codeReview -> save();

        $codeReviewMetric = $vstsProject -> code_review_metric;
        $vstsProject -> code_review_metric = ++$codeReviewMetric;
        $vstsProject -> save();
    }


    public function commentCreated( CommentCreatedEvent $event )
    {
        $vstsProject = $event -> vstsProject;

        $feedbackMetric = $vstsProject -> feedback_metric;
        $vstsProject -> feedback_metric = ++$feedbackMetric;
        $vstsProject -> save();
    }
}