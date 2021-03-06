<?php
/**
 * Created by PhpStorm.
 * Member: andreas
 * Date: 02/03/2017
 * Time: 18:44
 */


namespace App\Transformers;

use App\Models\Commit;
use App\Models\User;
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
                'edits' => $commit -> edits_counter,
                'deletes' => $commit -> deletes_counter
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
            $user = new User();
            $user -> username = $commit -> author_email;
        }

        return $this -> item( $user, new LightUserTransformer );
    }
}
