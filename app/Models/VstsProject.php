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
}
