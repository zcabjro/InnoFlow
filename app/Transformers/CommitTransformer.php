<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 02/03/2017
 * Time: 18:44
 */


namespace App\Transformers;

use App\Models\Commit;
use League\Fractal\TransformerAbstract;


class CommitTransformer extends TransformerAbstract
{
    protected $availableIncludes = [

    ];

    protected $defaultIncludes = [

    ];


    public function transform( Commit $commit )
    {
        return [

            'id' => $commit -> commit_id,
            'comment' => $commit -> comment,
            'date' => $commit -> date,

        ];
    }
}
