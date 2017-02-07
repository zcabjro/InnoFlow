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
     * Get the users (i.e. the admins) for the module.
     */
    public function users()
    {
        return $this -> belongsToMany( 'App\Models\User', 'admins', 'module_id', 'user_id' );
    }
}
