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
        $commit = $event -> commit;
        $vstsProject = $event -> vstsProject;

        if ( $vstsProject -> members() -> where( 'users.vsts_email', $commit -> author_email  ) -> count() == 0 )
        {
            return;
        }

        $commitCounter = $vstsProject -> commit_counter;
        $vstsProject -> commit_counter = ++$commitCounter;
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

        $codeReviewCounter = $vstsProject -> code_review_counter;
        $vstsProject -> code_review_counter = ++$codeReviewCounter;
        $vstsProject -> save();
    }


    public function commentCreated( CommentCreatedEvent $event )
    {
        $vstsProject = $event -> vstsProject;

        $feedbackCounter = $vstsProject -> feedback_counter;
        $vstsProject -> feedback_counter = ++$feedbackCounter;
        $vstsProject -> save();
    }
}