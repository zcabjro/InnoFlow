<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 04/03/2017
 * Time: 17:08
 */


namespace App\Repositories\Comment;

use App\Models\Comment;


class CommentRepo implements CommentRepoInterface
{
    private $model;


    public function __construct( Comment $model )
    {
        $this -> model = $model;
    }


    public function create( array $data )
    {
        return $this -> model -> create( $data );
    }
}