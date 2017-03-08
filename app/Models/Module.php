<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $primaryKey = 'module_id';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'code', 'key', 'user_id'
    ];


    /**
     * Gets all the admins of a module.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function admins()
    {
        return $this -> belongsToMany( 'App\Models\User', 'admins', 'module_id', 'user_id' );
    }


    /**
     * Gets all the projects of a module.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects()
    {
        return $this -> hasMany( 'App\Models\VstsProject', 'module_id', 'module_id' );
    }


    public function codeReviewMetric()
    {
        $metric = 0;
        $projects = $this -> projects;

        foreach ( $projects as $project )
        {
            $metric += $project -> codeReviewMetric();
        }

        return $metric / count( $projects );
    }


    public function commitBalanceMetric()
    {
        $metric = 0;
        $projects = $this -> projects;

        foreach ( $projects as $project )
        {
            $metric += $project -> commitBalanceMetric();
        }

        return $metric / count( $projects );
    }


    public function feedbackMetric()
    {
        $metric = 0;
        $projects = $this -> projects;

        foreach ( $projects as $project )
        {
            $metric += $project -> feedbackMetric();
        }

        return $metric / count( $projects );
    }
}
