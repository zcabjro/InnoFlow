<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Test\Support\IntegrationTrait;
use app\Models\User;

class InnovationTest extends TestCase
{
    use DatabaseTransactions;
    use IntegrationTrait;

    private $userCredentials = [
        'email' => 'abc@email.com',
        'password' => '1234567890'
    ];

    private $credentials = [

        'email' => 'YWJjQGVtYWlsLmNvbQ==',
        'password' => 'MTIzNDU2Nzg5MA==',
        'code' => 'c29tZSBjb2Rl'

    ];
    
    private $userCredentials2 = [
        'email' => 'def@email.com',
        'password' => '0987654321'
    ];

    private $credentials2 = [

        'email' => 'ZGVmQGVtYWlsLmNvbQ==',
        'password' => 'MDk4NzY1NDMyMQ==',
        'code' => 'd2hhdGV2ZXIgc3RyaW5n'

    ];

    /**
     * Successful store code.
     *
     * @return void
     */
    public function testStoreSuccessful()
    {
        // Create user
        $this -> createUser($this -> userCredentials);

        $this -> call('POST','api/innovations',$this -> credentials);
        $this -> seeInDataBase('innovations',['code' => $this -> credentials["code"]]);
    }

    /**
     * Fail to store code without permission (Invalid user credentials).
     *
     * @return void
     */
     public function testStoreWithoutPermission()
     {
         // Create user
         $this -> createUser($this -> userCredentials);
         
         // No user credentials
         $this -> call('POST','api/innovations',['code' => $this -> credentials["code"]]);
         $this -> seeJsonEquals([
             'error' => 'Permission denied. Invalid user credentials.'
         ]);

         // Incorrect email
         $this -> call('POST','api/innovations',['email' => 'YWJjZEBlbWFpbC5jb20=','password' => $this -> credentials["password"],'code' => $this -> credentials["code"]]);
         $this -> seeJsonEquals([
             'error' => 'Permission denied. Invalid user credentials.'
         ]);

         // Incorrect password
         $this -> call('POST','api/innovations',['email' => $this -> credentials["email"],'password' => 'MTMyNDU2Nzg5MA==','code' => $this -> credentials["code"]]);
         $this -> seeJsonEquals([
             'error' => 'Permission denied. Invalid user credentials.'
         ]);
     }

    /**
     * Fail to store code due to invalid code field.
     *
     * @return void
     */
     public function testStoreInvalidCodeField()
     {
         // Create user
         $this -> createUser($this -> userCredentials);

         // Empty code field
         $this -> call('POST','api/innovations',['email' => $this -> credentials["email"],'password' => $this -> credentials["password"]]);
         $this -> seeJsonEquals([
             'code' => ['The code field is required.']
         ]);

         // Code field is not Base64 encoded
         $this -> call('POST','api/innovations',['email' => $this -> credentials["email"],'password' => $this -> credentials["password"],'code' => '12345']);
         $this -> seeJsonEquals([
             'code' => ['The code must be base_64 encoded']
         ]);
     }

     /**
     * Unfinished
     * Successfully show innovations of user
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

        // Create data that user stored
        $this -> call('POST','api/innovations',$this -> credentials);

        // Create another user and stored another set of date_add
        $this -> createUser($this -> userCredentials2);
        $this -> call('POST','api/innovations',$this -> credentials2);
    
        $this -> call('GET','api/innovations');
        $this -> seeJson([
            'code' => $this -> credentials["code"]
        ]);
        $this -> dontSeeJson([
            'code' => $this -> credentials2["code"]
        ]);
        
     }

     /**
     * Fail to show innovations of user casued by expired token or haven't log in
     *
     * @return void
     */
     public function testIndexWithoutToken()
     {
         // Create user
         $this -> createUser($this -> userCredentials);
         
         $this -> call('GET','api/innovations');
         $this -> seeJsonEquals([
             'error' => 'Token does not exist anymore. Login again.'
         ]);
     }
}