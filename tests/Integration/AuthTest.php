<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Test\Support\IntegrationTrait;
use Test\Support\UserTrait;
use App\Models\User;
use App\Services\Vsts\VstsApiService;

class AuthTest extends TestCase
{
    use DatabaseTransactions;
    use IntegrationTrait;
    use UserTrait;

    /**
     * Represents valid credentials.
     *
     * @var array
     */
    private $credentials = [
        'email' => 'test@innoflow.com',
        'password' => 'password12345',
        'username' => 'testname'
    ];

    // Same email
    private $credentials2 = [
        'email' => 'test@innoflow.com',
        'password' => 'password12345',
        'username' => 'testname2'
    ];

    // Same username
    private $credentials3 = [
        'email' => 'test3@innoflow.com',
        'password' => 'password12345',
        'username' => 'testname'
    ];

    /**
     * Successful registration.
     *
     * @return void
     */
    public function testRegistrationSuccessful()
    {
        $this -> callRegister( $this -> credentials, 200 );
        $this -> seeInDatabase( 'users', [ 'email' => $this -> credentials[ "email" ] ] );
        $this -> assertNotEmpty( User::all() -> last() -> password );
        $this -> assertNotEquals( User::all() -> last() -> password, $this -> credentials[ "password" ] );
        $this -> assertEquals( User::all() -> last() -> username, $this -> credentials[ "username" ] );
    }

    /**
     * Unsuccessful registration caused by either a missing email, password or both.
     *
     * @return void
     */
    public function testRegistrationMissingParameters()
    {
        $this -> missingParametersRegister( array( $this, 'callRegister' ), $this -> credentials );
    }

    /**
     * Unsuccessful registration caused by either a wrongly formatted email, an already existing email or a too short password.
     *
     * @return void
     *
     */
    public function testRegistrationWrongParameters()
    {
        // Wrong email format
        $this -> callRegister( [ 'email' => 'andreas', 'password' => $this -> credentials[ "password" ], 'username' => $this -> credentials["username"] ], 422 );
        $this -> seeJsonEquals( [
            'email' => [ 'The email must be a valid email address.' ]
        ] );

        // Password too short
        $this -> callRegister( [ 'email' => $this -> credentials[ "email" ], 'password' => 'abc', 'username' => $this -> credentials["username"] ], 422 );
        $this -> seeJsonEquals( [
            'password' => [ 'The password must be at least 10 characters.' ]
        ] );

        // Username too short
        $this -> callRegister( [ 'email' => $this -> credentials[ "email" ], 'password' => $this -> credentials[ "password" ], 'username' => 'user' ], 422 );
        $this -> seeJsonEquals( [
            'username' => [ 'The username must be at least 5 characters.' ]
        ] );

        // Create existing user
        $this -> createUser( $this -> credentials );

        // Email already taken
        $this -> callRegister( $this -> credentials2, 422 );
        $this -> seeJsonEquals( [
            'email' => [ 'The email has already been taken.' ]
        ] );

        // Username already taken
        $this -> callRegister( $this -> credentials3, 422 );
        $this -> seeJsonEquals( [
            'username' => [ 'The username has already been taken.' ]
        ] );
    }

    /**
     * Successful login.
     *
     * @return void
     */
    public function testLoginSuccessful()
    {
        $this -> createUser( $this -> credentials );
        $this -> callLogin( [ 'email' => $this -> credentials["email"], 'password' => $this -> credentials["password"] ], 200 );
    }

    /**
     * Unsuccessful login caused by either a missing email, password or both.
     *
     * @return void
     */
    public function testLoginMissingParameters()
    {
        $this -> missingParametersLogin( array( $this, 'callLogin'), $this -> credentials );
    }

    /**
     * Successful logout.
     *
     * @return void
     */
     public function testLogoutSuccessful()
     {
        $this->withoutMiddleware();

        // Create user and login
        $this -> createUser($this -> credentials);
        $user = User::where('email',$this -> credentials)->first();
        $token = JWTAuth::fromUser($user);
        JWTAuth::setToken($token);

        $response = $this -> call('GET','api/logout');
        $this -> assertEquals(200, $response -> status());
     }

     /**
     * Unsuccessful logout due to expired token/haven't login.
     *
     * @return void
     */
     public function testLogoutWithoutToken()
     {
        $this -> call('GET','api/logout');
        $this -> seeJsonEquals( [
            'error' => 'Token does not exist anymore. Login again.'
        ] );
     }

     /**
     * Member is not authorized
     *
     * @return void
     */
     public function testTokenNotAuthorized()
     {
         $this->withoutMiddleware();

        // Create user and login
        $this -> createUser($this -> credentials);
        $user = User::where('email',$this -> credentials)->first();
        $token = JWTAuth::fromUser($user);
        JWTAuth::setToken($token);
        $user_id = $user -> user_id;

        $response = $this -> call('GET','api/vsts');
        //dd($response);
        $this -> seeJsonEquals( [
            'isAuthorized' => false,
            'url' => "https://app.vssps.visualstudio.com/oauth2/authorize?response_type=Assertion&state=$user_id"
        ] );
     }

     /**
     * Can not check authorization because token expired/haven't login
     *
     * @return void
     */
     public function testTokenNotExists()
     {
         $this -> call('GET','api/vsts');
        $this -> seeJsonEquals( [
            'error' => 'Token does not exist anymore. Login again.'
        ] );
     }

    /**
     * Make a post request trying to register a user.
     *
     * @param array $parameters
     * @param int $code
     */
    private function callRegister( $parameters, $code )
    {
        $this -> callRoute( 'POST', 'api/register', $parameters, $code );
    }

    /**
     * Make a post request trying to login a user.
     *
     * @param array $parameters
     * @param int $code
     */
    private function callLogin( $parameters, $code )
    {
        $this -> callRoute( 'POST', 'api/login', $parameters, $code );
    }

    /**
     * Checks if either email or password missing while login.
     *
     * @param
     * @param string $data
     * @return void
     */
    private function missingParametersLogin( $callback, $data )
    {
        // No email, password or username
        $callback( [], 422 );
        $this -> seeJsonEquals( [
            'email' => [ 'The email field is required.'],
            'password' => [ 'The password field is required.' ],
        ] );

        // No email
        $callback( [ 'password' => $data[ "password" ] ], 422 );
        $this -> seeJsonEquals( [
            'email' => [ 'The email field is required.' ]
        ] );

        // No password
        $callback( [ 'email' => $data[ "email" ] ], 422 );
        $this -> seeJsonEquals( [
            'password' => [ 'The password field is required.' ]
        ] );
    }

    /**
     * Checks if email, password or username are missing while register.
     *
     * @param
     * @param string $data
     * @return void
     */
    private function missingParametersRegister( $callback, $data )
    {
        // No email, password or username
        $callback( [], 422 );
        $this -> seeJsonEquals( [
            'email' => [ 'The email field is required.'],
            'password' => [ 'The password field is required.' ],
            'username' => [ 'The username field is required.']
        ] );

        // No email
        $callback( [ 'password' => $data[ "password" ], 'username' => $data[ "username" ] ], 422 );
        $this -> seeJsonEquals( [
            'email' => [ 'The email field is required.' ]
        ] );

        // No password
        $callback( [ 'email' => $data[ "email" ], 'username' => $data[ "username" ] ], 422 );
        $this -> seeJsonEquals( [
            'password' => [ 'The password field is required.' ]
        ] );

        // No username
        $callback( [ 'email' => $data[ "email" ], 'password' => $data[ "password" ] ], 422 );
        $this -> seeJsonEquals( [
            'username' => [ 'The username field is required.' ]
        ] );
    }
}
