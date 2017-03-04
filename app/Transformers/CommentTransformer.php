<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 04/03/2017
 * Time: 17:29
 */

namespace App\Transformers;

use App\Models\Comment;
use League\Fractal\TransformerAbstract;


class CommentTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'owner',
    ];


    public function transform( Comment $comment )
    {
        return [

            'id' => $comment -> comment_id,
            'date' => $comment -> created_at -> toDateTimeString(),
            'text' => $comment -> message,

        ];
    }


    /**
     * Include the owner.
     *
     * @param Comment $comment
     * @return \League\Fractal\Resource\Item
     */
    public function includeOwner( Comment $comment )
    {
        $user = $comment -> owner;
        return $this -> item( $user, new LightUserTransformer );
    }
}
