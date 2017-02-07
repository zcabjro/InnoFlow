<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use Notifiable;


    protected $primaryKey = 'user_id';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'vsts_access_token', 'vsts_refresh_token',
    ];


    /**
     * Get the modules the user is admin for.
     */
    public function modules()
    {
        return $this -> belongsToMany( 'App\Models\Module', 'admins', 'user_id', 'module_id' );
    }

    /**
     * Get the innovations the user has contributed.
     */
    public function innovations()
    {
        return $this -> hasMany( 'App\Models\Innovation', 'user_id' );
    }
}
