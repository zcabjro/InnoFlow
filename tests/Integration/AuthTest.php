<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Test\Support\IntegrationTrait;
use Test\Support\UserTrait;
use App\Models\User;



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

        'email' => 'andreas.buddy@gmail.com',
        'password' => 'password12345'

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
    }

    /**
     * Unsuccessful registration caused by either a missing email, password or both.
     *
     * @return void
     */
    public function testRegistrationMissingParameters()
    {
        $this -> missingParameters( array( $this, 'callRegister' ), $this -> credentials );
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
        $this -> callRegister( [ 'email' => 'andreas', 'password' => $this -> credentials[ "password" ] ], 422 );
        $this -> seeJsonEquals( [
            'email' => [ 'The email must be a valid email address.' ]
        ] );

        // Password to short
        $this -> callRegister( [ 'email' => $this -> credentials[ "email" ], 'password' => 'abc' ], 422 );
        $this -> seeJsonEquals( [
            'password' => [ 'The password must be at least 10 characters.' ]
        ] );

        // Email already taken
        $this -> createUser( $this -> credentials );
        $this -> callRegister( $this -> credentials, 422 );
        $this -> seeJsonEquals( [
            'email' => [ 'The email has already been taken.' ]
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
        $this -> callLogin( $this -> credentials, 200 );
    }

    /**
     * Unsuccessful login caused by either a missing email, password or both.
     *
     * @return void
     */
    public function testLoginMissingParameters()
    {
        $this -> missingParameters( array( $this, 'callLogin'), $this -> credentials );
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

        $this -> call('GET','api/vsts');
        $this -> seeJsonEquals( [
            'is_authorized' => false
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
     * Checks if either email, password or both are missing.
     *
     * @param
     * @param string $data
     * @return void
     */
    private function missingParameters( $callback, $data )
    {
        // No email or password
        $callback( [], 422 );
        $this -> seeJsonEquals( [
            'email' => [ 'The email field is required.'],
            'password' => [ 'The password field is required.' ]
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
}
