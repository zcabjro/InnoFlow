<?php
/**
 * Created by PhpStorm.
 * Member: andreas
 * Date: 19/02/2017
 * Time: 11:19
 */

namespace App\Repositories\VstsAccount;

use App\Models\VstsAccount;


class VstsAccountRepo implements VstsAccountRepoInterface
{
    private $model;

    function __construct( VstsAccount $model )
    {
        $this -> model = $model;
    }

    public function create( array $data )
    {
        return $this -> model -> create( $data );
    }

    public function find( $id )
    {
        return $this -> model -> find( $id );
    }
}