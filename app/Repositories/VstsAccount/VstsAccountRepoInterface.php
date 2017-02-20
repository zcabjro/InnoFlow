<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 19/02/2017
 * Time: 11:19
 */

namespace App\Repositories\VstsAccount;


use App\Models\VstsAccount;

interface VstsAccountRepoInterface
{
    /**
     * Finds a vsts account.
     *
     * @param $id
     * @return VstsAccount
     */
    public function find($id );

    /**
     * Creata a vsts account.
     *
     * @param array $data
     * @return VstsAccount
     */
    public function create(array $data );
}