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
        'deletes_counter',
        'is_complete'
    ];

    public function commiter()
    {
        return User::where( 'vsts_profile_id', $this -> profile_id ) -> first();
    }
}
