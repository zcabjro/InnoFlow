<?php

use App\Models\User;

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Creates a user in the database.
     *
     * @param array $data
     */
    public function createUser( array $data )
    {
        $token = array_key_exists( "vsts_access_token", $data ) ? $data[ "vsts_access_token" ] : null;

        $user = new User();
        $user -> email = $data[ "email" ];
        $user -> password = bcrypt( $data[ "password" ] );
        $user -> vsts_access_token = $token;
        $user -> save();
    }
}
