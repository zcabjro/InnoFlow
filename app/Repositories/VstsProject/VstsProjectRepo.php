<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 19/02/2017
 * Time: 15:05
 */

namespace App\Repositories\VstsProject;


use App\Models\VstsProject;


class VstsProjectRepo implements VstsProjectRepoInterface
{
    private $model;


    function __construct( VstsProject $model )
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


    public function update( array $data )
    {
        return $this -> model -> update( $data );
    }
}