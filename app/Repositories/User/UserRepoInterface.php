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
     * Logs in a user.
     *
     * @param array $input
     * @return string
     */
    public function login( $input );


    /**
     * Create a new user.
     *
     * @param array $input
     * @return User
     */
    public function create( array $input );


    /**
     * Update a user's information.
     *
     * @param $id
     * @param array $input
     * @return void
     */
    public function update( $id, array $input );


    /**
     * Finds a user based on a given email and password.
     *
     * @param $email
     * @param $password
     * @return boolean
     */
    public function findByCredentials( $email, $password );
}