<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodeReview extends Model
{
    protected $primaryKey = 'code_review_id';

    protected $fillable = [
        'user_id', 'project_id'
    ];


    /**
     * Gets the owner of the code review.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this -> belongsTo( 'App\Models\User', 'user_id', 'user_id' );
    }


    /**
     * Gets the commits of the code review.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function commits()
    {
        return $this -> belongsToMany( 'App\Models\Commit', 'code_review_commits', 'code_review_id', 'commit_id' );
    }


    /**
     * Gets the comments of the code review.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this -> hasMany( 'App\Models\Comment', 'code_review_id', 'code_review_id' ) -> orderBy( 'created_at', 'DESC' );
    }
}
