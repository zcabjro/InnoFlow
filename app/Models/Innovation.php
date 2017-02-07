<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Innovation extends Model
{
    protected $primaryKey = 'innovation_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code'
    ];

    /**
     * Get the user the innovation belongs to.
     */
    public function user()
    {
        return $this -> belongsTo( 'App\Models\User', 'user_id' );
    }
}
