<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 04/03/2017
 * Time: 16:38
 */

namespace App\Transformers;

use App\Models\CodeReview;
use League\Fractal\TransformerAbstract;


class CodeReviewTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'owner'
    ];

    protected $availableIncludes = [
        'commits', 'comments'
    ];

    public function transform( CodeReview $codeReview )
    {
        return [

            'id' => $codeReview -> code_review_id,
            'date' => $codeReview -> created_at -> toDateTimeString(),

        ];
    }


    /**
     * Include the owner.
     *
     * @param CodeReview $codeReview
     * @return \League\Fractal\Resource\Item
     */
    public function includeOwner( CodeReview $codeReview )
    {
        $user = $codeReview -> owner;
        return $this -> item( $user, new LightUserTransformer );
    }


    /**
     * Include the commits.
     *
     * @param CodeReview $codeReview
     * @return \League\Fractal\Resource\Collection
     */
    public function includeCommits( CodeReview $codeReview )
    {
        $commits = $codeReview -> commits;
        return $this -> collection( $commits, new CommitTransformer );
    }


    /**
     * Include the comments.
     *
     * @param CodeReview $codeReview
     * @return \League\Fractal\Resource\Collection
     */
    public function includeComments( CodeReview $codeReview )
    {
        $comments = $codeReview -> comments;
        return $this -> collection( $comments, new CommentTransformer );
    }
}
