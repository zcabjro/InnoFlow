<?php
/**
 * Created by PhpStorm.
 * Member: andreas
 * Date: 07/02/2017
 * Time: 13:09
 */

namespace App\Repositories\Innovation;

use App\Models\Innovation;

interface InnovationRepoInterface
{
    /**
     * Creates a new innovation.
     *
     * @param array $input
     * @return Innovation
     */
    public function create( array $input );
}