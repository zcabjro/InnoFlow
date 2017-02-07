<?php

/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 01/02/2017
 * Time: 16:31
 */

namespace App\Repositories\Module;


use App\Models\Module;
use App\Repositories\User\UserRepoInterface;
use Helper;


class ModuleRepo implements ModuleRepoInterface
{
    private $model;
    private $userRepo;


    public function __construct( UserRepoInterface $userRepo, Module $module )
    {
        $this -> model = $module;
        $this -> userRepo = $userRepo;
    }


    public function create( array $input )
    {
        $input[ 'user_id' ] = Helper::currentUser() -> user_id;
        return $this -> model -> create( $input );
    }
}