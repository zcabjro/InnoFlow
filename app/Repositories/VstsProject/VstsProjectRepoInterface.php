<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 19/02/2017
 * Time: 15:05
 */

namespace App\Repositories\VstsProject;


use App\Models\VstsProject;

interface VstsProjectRepoInterface
{
    /**
     * Creates a new vsts project.
     *
     * @param array $data
     * @return VstsProject
     */
    public function create( array $data );

    /**
     * Finds a vsts project by on an id.
     *
     * @param $id
     * @return VstsProject
     */
    public function find( $id );

    /**
     * Update details of a vsts project.
     *
     * @param array $data
     * @return VstsProject
     */
    public function update( array $data );
}