<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        for ( $i = 0; $i < 100; $i++ )
        {
            $user = new User();
            $user -> email = $faker -> unique() -> email;
            $user -> password = bcrypt( '1234567890' );
            $user -> username =  $faker -> unique() -> userName;
            $user -> save();
        }

        $user = new User();
        $user -> email = getenv( 'LOCAL_EMAIL' );
        $user -> password = bcrypt( getenv( 'LOCAL_PASSWORD' ) );
        $user -> username =  getenv( 'LOCAL_USERNAME' );
        $user -> vsts_access_token = getenv( 'LOCAL_VSTS_ACCESS_TOKEN' );
        $user -> vsts_refresh_token = getenv( 'LOCAL_VSTS_REFRESH_TOKEN' );
        $user -> save();
    }
}
