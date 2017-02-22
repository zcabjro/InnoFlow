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
        $user -> email = 'jack@gmail.com';
        $user -> password = bcrypt( '1234567890' );
        $user -> username =  'Crocodile Killer';
        $user -> vsts_access_token = '';
        $user -> vsts_refresh_token = '';
        $user -> save();
    }
}
