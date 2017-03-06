<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VstsProject extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'project_id';

    protected $fillable = [
        'project_id', 'account_id', 'name', 'description', 'revision'
    ];


    /**
     * Gets the account the project belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this -> belongsTo( 'App\Models\VstsAccount', 'account_id', 'account_id' );
    }


    /**
     * Gets the module the project is enrolled with.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function module()
    {
        return $this -> belongsTo( 'App\Models\Module', 'module_id', 'module_id' );
    }


    /**
     * Gets the commits of the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function commits()
    {
        return $this -> hasMany( 'App\Models\Commit', 'project_id', 'project_id' ) -> orderBy( 'date', 'DESC' );
    }


    /**
     * Gets the code review discussions of the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function codeReviews()
    {
        return $this -> hasMany( 'App\Models\CodeReview', 'project_id', 'project_id' ) -> orderBy( 'created_at', 'DESC' );
    }


    /**
     * Gets the members of the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function members()
    {
        return $this -> belongsToMany( 'App\Models\User', 'vsts_project_users', 'project_id', 'user_id' );
    }
}
