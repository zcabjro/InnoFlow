<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 02/03/2017
 * Time: 16:55
 */


namespace App\Repositories\Commit;

use App\Models\Commit;


class CommitRepo implements CommitRepoInterface
{
    private $model;


    public function __construct( Commit $model )
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