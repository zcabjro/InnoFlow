<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $primaryKey = 'comment_id';

    protected $fillable = [
        'user_id',
        'code_review_id',
        'message'
    ];


    /**
     * Gets the owner of the comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this -> belongsTo( 'App\Models\User', 'user_id', 'user_id' );
    }
}
