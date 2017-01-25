<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Test\Support\IntegrationTrait;
use App\Models\User;



class AuthTest extends TestCase
{
    use DatabaseTransactions;
    use IntegrationTrait;



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

    /**
     * Creates a user in the database.
     *
     * @param array $data
     */
    private function createUser( array $data )
    {
        $vstsToken = array_key_exists( "vsts_token", $data ) ? $data[ "vsts_token" ] : null;

        $user = new User();
        $user -> email = $data[ "email" ];
        $user -> password = bcrypt( $data[ "password" ] );
        $user -> vsts_token = $vstsToken;
        $user -> save();
    }
}
