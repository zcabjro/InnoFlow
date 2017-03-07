<?php
/**
 * Created by PhpStorm.
 * Member: andreas
 * Date: 07/02/2017
 * Time: 13:09
 */


namespace App\Repositories\Innovation;

use App\Models\Innovation;


class InnovationRepo implements InnovationRepoInterface
{
    private $model;


    public function __construct( Innovation $model )
    {
        $this -> model = $model;
    }


    public function create( array $input )
    {
        $innovation = new Innovation();
        $innovation -> code = $input[ 'code' ];
        $innovation -> user() -> associate( $input[ 'user' ] );
        $innovation -> save();
        return $innovation;
    }
}