<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 04/03/2017
 * Time: 16:21
 */


namespace App\Repositories\CodeReview;

use App\Models\CodeReview;

interface CodeReviewRepoInterface
{
    /**
     * Creates a new code review.
     *
     * @param array $data
     * @return CodeReview
     */
    public function create( array $data );
}