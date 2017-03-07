<?php
/**
 * Created by PhpStorm.
 * Member: andreas
 * Date: 04/03/2017
 * Time: 16:21
 */


namespace App\Repositories\CodeReview;

use App\Models\CodeReview;


class CodeReviewRepo implements CodeReviewRepoInterface
{
    private $model;


    public function __construct( CodeReview $model )
    {
        $this -> model = $model;
    }


    public function create( array $data )
    {
        return $this -> model -> create( $data );
    }
}