<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'user_id';

    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'username',
        'vsts_last_account_update',
        'vsts_last_project_update'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'vsts_access_token',
        'vsts_refresh_token'
    ];


    /**
     * Resets a user's vsts details.
     */
    public function resetVsts()
    {
        $this -> vsts_access_token = null;
        $this -> vsts_refresh_token = null;
        $this -> vsts_profile_id = null;
        $this -> vsts_email = null;
        $this -> save();
    }


    /**
     * Gets all the modules the user is admin for.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function modules()
    {
        return $this
            -> belongsToMany( 'App\Models\Module', 'admins', 'user_id', 'module_id' )
            -> withPivot( 'is_owner' );
    }


    /**
     * Gets all the innovations the user has contributed.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function innovations()
    {
        return $this -> hasMany( 'App\Models\Innovation', 'user_id' ) -> orderBy( 'created_at', 'DESC' );
    }


    /**
     * Gets all the accounts the user has access to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function accounts()
    {
        return $this
            -> belongsToMany( 'App\Models\VstsAccount', 'vsts_account_users', 'user_id', 'account_id' )
            -> withPivot( 'is_owner' );
    }


    /**
     * Gets all projects that the user is member of
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function projects()
    {
        return $this -> belongsToMany( 'App\Models\VstsProject', 'vsts_project_users', 'user_id', 'project_id' );
    }


    public function codeReviewMetric( VstsProject $vstsProject )
    {
        $individual = $vstsProject -> activeCodeReviews() -> where( 'user_id', $this -> user_id ) -> count();
        return $vstsProject -> code_review_counter == 0 ? 0 : ( $individual / $vstsProject -> code_review_counter ) * 100;
    }


    public function commitBalanceMetric( VstsProject $vstsProject )
    {
        $individual = $vstsProject -> commits() -> where( 'author_email', $this -> vsts_email ) -> count();

        $contributed = ( $individual / $vstsProject -> commit_counter ) * 100;
        $expected = 100 / $vstsProject -> members() -> count();

        $metric = $contributed >= $expected ? 1 : $contributed / $expected ;

        return $metric;
    }


    public function feedbackMetric( VstsProject $vstsProject  )
    {
        $individual = $vstsProject -> comments() -> where( 'user_id', $this -> user_id ) -> count();
        return $vstsProject -> feedback_counter == 0 ? 0 : ( $individual / $vstsProject -> feedback_counter ) * 100;
    }
}
