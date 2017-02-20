<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 01/02/2017
 * Time: 16:32
 */

namespace App\Repositories\Module;

use App\Models\Module;


interface ModuleRepoInterface
{
    /**
     * Creates a new module.
     *
     * @param array $input
     * @return Module
     */
    public function create( array $input );


    /**
     * Find a module by its credentials.
     *
     * @param $code
     * @param $key
     * @return mixed
     */
    public function findByCredentials( $code, $key );
}