<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 19/02/2017
 * Time: 18:15
 */


namespace App\Transformers;

use App\Models\VstsProject;
use League\Fractal\TransformerAbstract;


class VstsProjectTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'members', 'commits'
    ];

    protected $defaultIncludes = [

    ];


    public function transform( VstsProject $project )
    {
        $fields = [
            'id' => $project -> project_id
        ];

        if ( !is_null( $name = $project -> name ) )
        {
            $fields[ 'name' ] = $name;
        }

        if ( !is_null( $description = $project -> description ) )
        {
            $fields[ 'description' ] = $description;
        }

        if ( !is_null( $moduleId = $project -> module_id ) )
        {
            $fields[ 'classId' ] = $moduleId;
        }

        if ( !is_null( $isOwner = $project -> is_owner ) )
        {
            $fields[ 'isOwner' ] = ( boolean ) $isOwner;
        }

        return $fields;
    }


    /**
     * Include members.
     *
     * @param VstsProject $project
     * @return \League\Fractal\Resource\Collection
     */
    public function includeMembers( VstsProject $project )
    {
        $users = $project -> members;
        return $this -> collection( $users, new UserTransformer );
    }


    /**
     * Include commits.
     *
     * @param VstsProject $project
     * @return \League\Fractal\Resource\Collection
     */
    public function includeCommits( VstsProject $project )
    {
        $commits = $project -> commits;
        return $this -> collection( $commits, new CommitTransformer );
    }
}