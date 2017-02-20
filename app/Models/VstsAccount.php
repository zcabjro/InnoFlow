<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VstsAccount extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'account_id';

    protected $fillable = [
        'account_id', 'name'
    ];


    /**
     * Gets all users that have access to the account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this
            -> belongsToMany( 'App\Models\User', 'vsts_account_users', 'account_id', 'user_id' )
            -> withPivot( 'is_owner' );
    }


    /**
     * Gets all projects that belong to an account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects()
    {
        return $this -> hasMany( 'App\Models\VstsProject', 'account_id', 'account_id' );
    }


    /**
     * Gets the owner of the account.
     *
     * @return mixed
     */
    public function owner()
    {
        return $this -> users() -> where( 'is_owner', true ) -> first();
    }
}
