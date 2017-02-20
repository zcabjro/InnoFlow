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
}
