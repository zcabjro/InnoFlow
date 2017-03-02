<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 02/03/2017
 * Time: 16:56
 */

namespace App\Repositories\Commit;

use App\Models\Commit;

interface CommitRepoInterface
{
    /**
     * Creates a new commit.
     *
     * @param array $input
     * @return Commit
     */
    public function create( array $input );

    /**
     * Finds a commit by id.
     *
     * @param $id
     * @return Commit
     */
    public function find( $id );
}