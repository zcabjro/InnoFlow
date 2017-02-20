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
use Illuminate\Support\Facades\Hash;


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
        return $this -> model -> create( $input );
    }


    public function findByCredentials( $code, $key )
    {
        if ( is_null( $module = $this -> model-> where( 'code', $code ) -> first() ) )
        {
            return null;
        }

        if ( !Hash::check( $key, $module -> key ) )
        {
            return null;
        }

        return $module;
    }
}