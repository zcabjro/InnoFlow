<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 03/03/2017
 * Time: 18:56
 */

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;


class LightUserTransformer extends TransformerAbstract
{
    public function transform( User $user )
    {
        return [

            'id' => $user -> user_id,
            'username' => $user -> username

        ];
    }
}