<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 03/03/2017
 * Time: 18:48
 */


namespace App\Transformers;

use App\Models\Commit;
use League\Fractal\TransformerAbstract;


class LightCommitTransformer extends TransformerAbstract
{
    public function transform( Commit $commit )
    {
        return [

            'id' => $commit -> commit_id,
            'comment' => $commit -> comment,
            'date' => $commit -> date,

        ];
    }
}
