<?php
/**
 * Created by PhpStorm.
 * Member: andreas
 * Date: 07/02/2017
 * Time: 22:28
 */


namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Innovation;


class InnovationTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [

    ];

    public function transform( Innovation $innovation )
    {
        return [

            'code' => $innovation -> code,
            'created' => $innovation -> created_at -> toDateTimeString()

        ];
    }
}