<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Test\Support\IntegrationTrait;
use Test\Support\UserTrait;
use app\Models\User;
use app\Models\Module;

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

    private $classSetting3 = [
        'name' => 'Testing3',
        'description' => 'For testing purpose 3',
        'code' => 'TEST003',
        'key' => 'TESTKEY003'
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
        $user = User::where('email',$this -> userCredentials)->first();
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
        $user = User::where('email',$this -> userCredentials)->first();
        $token = JWTAuth::fromUser($user);
        JWTAuth::setToken($token);

        // Create second user as admin
        $this -> createUser($this -> userCredentials2);
        $user2 = User::where('email',$this -> userCredentials2)->first();
        $token2 = JWTAuth::fromUser($user2);
        $admin_id = $user2 -> user_id;

        // Create data stored by first user
        $this -> call('POST','api/classes',[
            'name' => $this -> classSetting["name"],
            'description' => $this -> classSetting["description"],
            'code' => $this -> classSetting["code"],
            'key' => $this -> classSetting["key"],
            'admins' => "$admin_id"
        ]);

        JWTAuth::setToken($token2);

        // Get module id stored by first user
        $module = Module::where('code',$this -> classSetting["code"])->first();
        $module_id = $module -> module_id;

        // The second user can see module stored by the first user as he is admin
        $this -> call('GET','api/classes');
        $this -> seeJsonEquals([[
            'id' => $module_id,
            'name' => $this -> classSetting["name"],
            'description' => $this -> classSetting["description"],
            'code' => $this -> classSetting["code"]
        ]]);

        // Create third user
        $this -> createUser($this -> userCredentials3);
        $user3 = User::where('email',$this -> userCredentials3)->first();
        $token3 = JWTAuth::fromUser($user3);
        JWTAuth::setToken($token3);

        // Create data stored by third user
        $this -> call('POST','api/classes',[
            'name' => $this -> classSetting3["name"],
            'description' => $this -> classSetting3["description"],
            'code' => $this -> classSetting3["code"],
            'key' => $this -> classSetting3["key"]
        ]);

        // Get module id stored by third user
        $module = Module::where('code',$this -> classSetting3["code"])->first();
        $module_id = $module -> module_id;

        // The third user only see module stored by him, can't see module stored by the first user
        $this -> call('GET','api/classes');
        $this -> seeJsonEquals([[
            'id' => $module_id,
            'name' => $this -> classSetting3["name"],
            'description' => $this -> classSetting3["description"],
            'code' => $this -> classSetting3["code"]
        ]]);
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
     * Successful show details of a selected module
     *
     * @return void
     */
     // Returning empty
     public function testShowSuccessful()
     {
        $this->withoutMiddleware();

        // Create user
        $this -> createUser($this -> userCredentials);
        $user = User::where('email',$this -> userCredentials)->first();
        $token = JWTAuth::fromUser($user);
        JWTAuth::setToken($token);

        // Create data stored by user
        $this -> call('POST','api/classes',[
            'name' => $this -> classSetting["name"],
            'description' => $this -> classSetting["description"],
            'code' => $this -> classSetting["code"],
            'key' => $this -> classSetting["key"]
        ]);

        // Get module id
        $module = Module::where('code',$this -> classSetting["code"])->first();
        $module_id = $module -> module_id;

        // Get all module(s)
        $this -> call('GET','api/classes');
        $this -> seeJsonEquals([[
            'id' => $module_id,
            'name' => $this -> classSetting["name"],
            'description' => $this -> classSetting["description"],
            'code' => $this -> classSetting["code"]
        ]]);

        // Get details of first module
        $this -> call('GET','api/classes/'.$module_id);
        $this -> seeJsonEquals([
            'id' => $module_id,
            'name' => $this -> classSetting["name"],
            'description' => $this -> classSetting["description"],
            'code' => $this -> classSetting["code"]
        ]);
     }

    /**
     * Failed to show details of a selected module due to expired token / haven't login
     *
     * @return void
     */
     public function testShowWithoutToken()
     {    
        $this -> withoutMiddleware();

        // Create user
        $this -> createUser($this -> userCredentials);
        $user = User::where('email',$this -> userCredentials)->first();
        $token = JWTAuth::fromUser($user);
        JWTAuth::setToken($token);

        // Create data stored by first user
        $this -> call('POST','api/classes',[
            'name' => $this -> classSetting["name"],
            'description' => $this -> classSetting["description"],
            'code' => $this -> classSetting["code"],
            'key' => $this -> classSetting["key"]
        ]);

        $this -> instance('middleware.disable',false);

        // Get module id
        $module = Module::where('code',$this -> classSetting["code"])->first();
        $module_id = $module -> module_id;
        $url = "api/classes/$module_id";

        $this -> call('GET',$url);
        $this -> seeJsonEquals([
            'error' => 'Token does not exist anymore. Login again.'
        ]);
     }

    /**
     * Successful search module(s) related to search request.
     *
     * @return void
     */
     public function testSearchModuleSuccessful()
     {
        $this -> withoutMiddleware();

        // Create users
        $this -> createUser($this -> userCredentials);
        $user = User::where('email',$this -> userCredentials)->first();
        $token = JWTAuth::fromUser($user);
        JWTAuth::setToken($token);

        // Create data stored by first user
        $this -> call('POST','api/classes',[
            'name' => $this -> classSetting["name"],
            'description' => $this -> classSetting["description"],
            'code' => $this -> classSetting["code"],
            'key' => $this -> classSetting["key"]
        ]);

        // Get module id
        $module = Module::where('code',$this -> classSetting["code"])->first();
        $module_id = $module -> module_id;

        // Search module by code
        $this -> call('GET','api/classes/search',['string' => $this -> classSetting["code"]]);
        $this -> seeJsonEquals([[
            'id' => $module_id,
            'name' => $this -> classSetting["name"],
            'description' => $this -> classSetting["description"],
            'code' => $this -> classSetting["code"]
        ]]);

        // Search module by name
        $this -> call('GET','api/classes/search',['string' => $this -> classSetting["name"]]);
        $this -> seeJsonEquals([[
            'id' => $module_id,
            'name' => $this -> classSetting["name"],
            'description' => $this -> classSetting["description"],
            'code' => $this -> classSetting["code"]
        ]]);

        // Search module doesn't exist
        $this -> call('GET','api/classes/search',['string' => 'Module does not exist']);
        $this -> seeJsonEquals([]);
     }

    /**
     * Failed to search for module(s) due to invalid search string
     *
     * @return void
     */
     public function testSearchModuleInvalidString()
     {
         $this -> withoutMiddleware();

         // Empty string field
         $this -> call('GET','api/classes/search',['string' => '']);
         $this -> seeJsonEquals([
            'string' => ['The string field is required.']
         ]);

         // Invalid string field (Less than two characters)
         $this -> call('GET','api/classes/search',['string' => 'a']);
         $this -> seeJsonEquals([
             'string' => ['The string must be at least 2 characters.']
         ]);
     }

    /**
     * Failed to search for user(s) due to expired token/haven't log in
     *
     * @return void
     */
     public function testSearchModuleWithoutToken()
     {
        $this -> call('GET','api/classes/search',['string' => $this -> classSetting["code"]]);
        $this -> seeJsonEquals([
            'error' => 'Token does not exist anymore. Login again.'
        ]);
     }

    /**
     * Successful search admin(s) related to search request.
     *
     * @return void
     */
     public function testSearchAdminsSuccessful()
     {
        $this -> withoutMiddleware();

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

        // Search for user that does not exist
        $this -> call('GET','api/classes/admins/search',['string' => 'userNotExist@email.com']);
        $this -> seeJson([]);
     }

    /**
     * Failed to search for user(s) due to invalid search string
     *
     * @return void
     */
     public function testSearchAdminInvalidString()
     {
         $this -> withoutMiddleware();

         // Empty string field
         $this -> call('GET','api/classes/admins/search',['string' => '']);
         $this -> seeJsonEquals([
            'string' => ['The string field is required.']
         ]);

         // Invalid string field (Less than two characters)
         $this -> call('GET','api/classes/admins/search',['string' => 'a']);
         $this -> seeJsonEquals([
             'string' => ['The string must be at least 2 characters.']
         ]);
     } 

    /**
     * Failed to search for user(s) due to expired token/haven't log in
     *
     * @return void
     */
     public function testSearchAdminWithoutToken()
     {
        $this -> call('GET','api/classes/admins/search',['string' => $this -> userCredentials["email"]]);
        $this -> seeJsonEquals([
            'error' => 'Token does not exist anymore. Login again.'
        ]);
     }
}