<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commit extends Model
{
    protected $primaryKey = 'commit_id';

    public $incrementing = false;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'commit_id',
        'project_id',
        'comment',
        'date',
        'details_url',
        'profile_id',
        'web_url',
        'adds_counter',
        'edits_counter',
        'deletes_counter'
    ];


    public function commiter()
    {
        return User::where( 'vsts_email', $this -> author_email ) -> first();
    }


    /**
     * Gets the code reviews the commit is associated with.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function codeReviews()
    {
        return $this -> belongsToMany( 'App\Models\CodeReview', 'code_review_commits', 'commit_id', 'code_review_id' );
    }
}
