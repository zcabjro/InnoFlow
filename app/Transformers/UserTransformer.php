<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 18/01/2017
 * Time: 14:07
 */

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\User;


class UserTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [

    ];

    public function transform( User $user )
    {
        return [

            'userId' => $user -> user_id,
            'email' => $user -> email,
            'username' => $user -> username

        ];
    }
}