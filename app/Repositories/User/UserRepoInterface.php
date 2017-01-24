<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 23/01/2017
 * Time: 14:35
 */


namespace App\Repositories\User;


use App\Models\User;


interface UserRepoInterface
{
    /**
     * @param array $input
     * @return string
     */
    public function login( $input );


    /**
     * @return void
     */
    public function logout();


    /**
     * @return User $user
     */
    public function loggedIn();


    /**
     * @param array $input
     * @return void
     */
    public function create( array $input );


    /**
     * @param $id
     * @param array $input
     * @return void
     */
    public function update( $id, array $input );
}