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
    protected $defaultIncludes = [
        'commiter'
    ];

    public function transform( Commit $commit )
    {
        return [

            'id' => $commit -> commit_id,
            'comment' => $commit -> comment,
            'date' => $commit -> date,
            'commit_url' => $commit -> web_url,
            'changes' => [
                'adds' => $commit -> adds_counter,
                'edits' => $commit -> edits_counter
            ]

        ];
    }


    /**
     * Include commiter.
     *
     * @param Commit $commit
     * @return \League\Fractal\Resource\Item
     */
    public function includeCommiter( Commit $commit )
    {
        $user = $commit -> commiter();

        if ( is_null( $user ) )
        {
            return null;
        }

        return $this -> item( $user, new LightUserTransformer );
    }
}
