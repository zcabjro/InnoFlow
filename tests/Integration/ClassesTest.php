<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Test\Support\IntegrationTrait;
use Test\Support\UserTrait;
use app\Models\User;

class ClassesTest extends TestCase
{
    use DatabaseTransactions;
    use IntegrationTrait;
    use UserTrait;

    private $userCredentials = [
        'email' => 'abc@email.com',
        'password' => '1234567890'
    ];

    private $userCredentials2 = [
        'email' => 'abdcef@email.com',
        'password' => '1234567890'
    ];

    private $userCredentials3 = [
        'email' => 'adef@email.com',
        'password' => '1234567890'
    ];

    private $classSetting = [
        'name' => 'Testing',
        'description' => 'For testing purpose 1',
        'code' => 'TEST001',
        'key' => 'TESTKEY001'
    ];

    private $classSetting2 = [
        'name' => 'Testing2',
        'description' => 'For testing purpose 2',
        'code' => 'TEST001',
        'key' => 'TESTKEY002'
    ];

    /**
     * Successful store class.
     *
     * @return void
     */
    public function testStoreSuccessful()
    {
        $this->withoutMiddleware();

        // Create user
        $this -> createUser($this -> userCredentials);
        $user = User::where('email',$this -> userCredentials)->first();
        $token = JWTAuth::fromUser($user);
        JWTAuth::setToken($token);

        $this -> call('POST','api/classes',$this -> classSetting);

        $this -> seeInDataBase('modules',[
            'name' => $this -> classSetting["name"],
            'description' => $this -> classSetting["description"],
            'code' => $this -> classSetting["code"]
        ]);
    }

    /**
     * Fail to store class for user casued invalid fields
     *
     * @return void
     */
     public function testStoreWithInvalidFields()
     {
         $this->withoutMiddleware();

        // Create user
        $this -> createUser($this -> userCredentials);
        $user = User::first();
        $token = JWTAuth::fromUser($user);
        JWTAuth::setToken($token);

        // Empty fields
        $this -> call('POST','api/classes');
        $this -> seeJsonEquals([
            'name' => [ 'The name field is required.'],
            'description' => [ 'The description field is required.'],
            'code' => [ 'The code field is required.'],
            'key' => [ 'The key field is required.']
        ]);

        // Empty name field
        $this -> call('POST','api/classes',[
            'description' => $this -> classSetting["description"],
            'code' => $this -> classSetting["code"],
            'key' => $this -> classSetting["key"]
        ]);
        $this -> seeJsonEquals([
            'name' => [ 'The name field is required.']
        ]);

        // Empty description field
        $this -> call('POST','api/classes',[
            'name' => $this -> classSetting["name"],
            'code' => $this -> classSetting["code"],
            'key' => $this -> classSetting["key"]
        ]);
        $this -> seeJsonEquals([
            'description' => [ 'The description field is required.']
        ]);

        // Empty code field
        $this -> call('POST','api/classes',[
            'name' => $this -> classSetting["name"],
            'description' => $this -> classSetting["description"],
            'key' => $this -> classSetting["key"]
        ]);
        $this -> seeJsonEquals([
            'code' => [ 'The code field is required.']
        ]);

        // Empty key field
        $this -> call('POST','api/classes',[
            'name' => $this -> classSetting["name"],
            'description' => $this -> classSetting["description"],
            'code' => $this -> classSetting["code"]
        ]);
        $this -> seeJsonEquals([
            'key' => [ 'The key field is required.']
        ]);
       
        // Existed Code (Taken)
        $this -> call('POST','api/classes',$this -> classSetting);
        $this -> call('POST','api/classes',$this -> classSetting2);
        $this -> seeJsonEquals([
            'code' => [ 'The code has already been taken.']
        ]);
     }

    /**
     * Fail to store class for user casued by expiried token or haven't log in
     *
     * @return void
     */
     public function testStoreWithoutToken()
     {
         // Create user
         $this -> createUser($this -> userCredentials);

         $this -> call('POST','api/classes');
         $this -> seeJsonEquals([
             'error' => 'Token does not exist anymore. Login again.'
         ]);
     }

    /**
     * Successful show classes.
     *
     * @return void
     */
    public function testIndexSuccessful()
    {
        $this->withoutMiddleware();

        // Create user
        $this -> createUser($this -> userCredentials);
        $user = User::first();
        $token = JWTAuth::fromUser($user);
        JWTAuth::setToken($token);

        // Create data stored by user
        $this -> call('POST','api/classes',$this -> classSetting);

        $this -> call('GET','api/classes');
        $this -> seeJson([
            'name' => $this -> classSetting["name"],
            'code' => $this -> classSetting["code"]
        ]);
    }

    /**
     * Fail to show classes of user casued by expiried token or haven't log in
     *
     * @return void
     */
     public function testIndexWithoutToken()
     {
         // Create user
         $this -> createUser($this -> userCredentials);

         $this -> call('GET','api/classes');
         $this -> seeJsonEquals([
             'error' => 'Token does not exist anymore. Login again.'
         ]);
     }

     /**
     * Successful show users related to search request.
     *
     * @return void
     */
    public function testShowRequestResultSuccessful()
    {
        $this->withoutMiddleware();

        // Create users
        $this -> createUser($this -> userCredentials);
        $this -> createUser($this -> userCredentials2);
        $this -> createUser($this -> userCredentials3);

        $user = User::where('email',$this -> userCredentials)->first();
        $token = JWTAuth::fromUser($user);
        JWTAuth::setToken($token);

        // Search for users that exists
        $this -> call('GET','api/classes/admins/search',['string' => $this -> userCredentials["email"]]);
        $this -> seeJson([
            'email' => $this ->userCredentials["email"]
        ]);
        $this -> seeJson([
            'email' => $this ->userCredentials2["email"]
        ]);
        $this -> dontSeeJson([
            'email' => $this ->userCredentials3["email"]
        ]);

        // Search for user that does not exists
        $this -> call('GET','api/classes/admins/search',['string' => 'xyz@email.com']);
        $this -> seeJson([]);
    }

    /**
     * Failed to search for user(s) due to expired token/haven't log in
     *
     * @return void
     */
     public function testSearchWithoutToken()
     {
        $this -> call('GET','api/classes/admins/search',['string' => $this -> userCredentials["email"]]);
        $this -> seeJsonEquals([
            'error' => 'Token does not exist anymore. Login again.'
        ]);
     }
}