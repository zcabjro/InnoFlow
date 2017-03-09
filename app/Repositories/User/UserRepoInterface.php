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
     * @param array $data
     * @return string
     */
    public function login( $data );


    /**
     * Create a new user.
     *
     * @param array $data
     * @return User
     */
    public function create( array $data );

    /**
     * Finds a user by id.
     *
     * @param $id
     * @return User
     */
    public function find( $id );


    /**
     * Find a user based on a column value.
     *
     * @param $attribute
     * @param $value
     * @return mixed
     */
    public function findBy( $attribute, $value );

    /**
     * Update a user's information.
     *
     * @param $id
     * @param array $data
     * @return void
     */
    public function update( $id, array $data );


    /**
     * Finds a user based on a given email and password.
     *
     * @param $email
     * @param $password
     * @return boolean
     */
    public function findByCredentials( $email, $password );
}