<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Test\Support\IntegrationTrait;
use Test\Support\UserTrait;
use app\Models\User;

class InnovationTest extends TestCase
{
    use DatabaseTransactions;
    use IntegrationTrait;
    use UserTrait;

    private $userCredentials = [
        'email' => 'abc@email.com',
        'password' => '1234567890'
    ];

    private $codeCredentials = [
        'code' => 'some code',
        'encoded_code' => 'c29tZSBjb2Rl'
    ];
    
    private $userCredentials2 = [
        'email' => 'def@email.com',
        'password' => '0987654321'
    ];

    private $codeCredentials2 = [
        'code' => 'whatever string',
        'encoded_code' => 'd2hhdGV2ZXIgc3RyaW5n'
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

        $this -> call('POST','api/innovations',['email' => $this -> userCredentials['email'],'password' => $this -> userCredentials['password'], 'code' => $this -> codeCredentials["encoded_code"]]);
        $this -> seeInDataBase('innovations',['code' => $this -> codeCredentials["code"]]);
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
         $this -> call('POST','api/innovations',['code' => $this -> codeCredentials["encoded_code"]]);
         $this -> seeJsonEquals([
             'error' => 'Permission denied. Invalid user credentials.'
         ]);

         // Incorrect email
         $this -> call('POST','api/innovations',['email' => 'abd@email.com','password' => $this -> userCredentials["password"],'code' => $this -> codeCredentials["encoded_code"]]);
         $this -> seeJsonEquals([
             'error' => 'Permission denied. Invalid user credentials.'
         ]);

         // Incorrect password
         $this -> call('POST','api/innovations',['email' => $this -> userCredentials["email"],'password' => '1234567800','code' => $this -> codeCredentials["encoded_code"]]);
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
         $this -> call('POST','api/innovations',['email' => $this -> userCredentials["email"],'password' => $this -> userCredentials["password"]]);
         $this -> seeJsonEquals([
             'code' => ['The code field is required.']
         ]);

         // Code field is not Base64 encoded
         $this -> call('POST','api/innovations',['email' => $this -> userCredentials["email"],'password' => $this -> userCredentials["password"],'code' => '12345']);
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
        $user = User::where('email',$this -> userCredentials)->first();
        $token = JWTAuth::fromUser($user);
        JWTAuth::setToken($token);

        // Create data that user stored
        $this -> call('POST','api/innovations',['email' => $this -> userCredentials['email'],'password' => $this -> userCredentials['password'],'code' => $this -> codeCredentials['encoded_code']]);

        // Create another user and stored another set of date_add
        $this -> createUser($this -> userCredentials2);
        $this -> call('POST','api/innovations',['email' => $this -> userCredentials2['email'],'password' => $this -> userCredentials2['password'],'code' => $this -> codeCredentials2['encoded_code']]);
    
        $this -> call('GET','api/innovations');
        $this -> seeJson([
            'code' => $this -> codeCredentials["code"]
        ]);
        $this -> dontSeeJson([
            'code' => $this -> codeCredentials2["code"]
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