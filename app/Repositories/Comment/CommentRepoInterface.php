<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 04/03/2017
 * Time: 17:08
 */

namespace App\Repositories\Comment;

use App\Models\Comment;

interface CommentRepoInterface
{
    /**
     * Creates a new code review.
     *
     * @param array $data
     * @return Comment
     */
    public function create( array $data );
}