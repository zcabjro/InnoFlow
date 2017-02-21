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
        $user -> vsts_access_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsIng1dCI6Im9PdmN6NU1fN3AtSGpJS2xGWHo5M3VfVjBabyJ9.eyJuYW1laWQiOiJiMTNhY2NkMi00OTllLTZjZTAtYWY2NS0yYmY3OWJmMTAxN2EiLCJzY3AiOiJ2c28uY29kZSB2c28ucHJvamVjdF9tYW5hZ2UiLCJpc3MiOiJhcHAudnNzcHMudmlzdWFsc3R1ZGlvLmNvbSIsImF1ZCI6ImFwcC52c3Nwcy52aXN1YWxzdHVkaW8uY29tIiwibmJmIjoxNDg3Njc4NzQwLCJleHAiOjE0ODc2ODIzNDB9.LaPLCcskNTZ8ERiKFUnOfkepZWJSAlttpzpzhiGIfrL4EWsPdMNyYfcqIMLwOd2w8odM0HWTPnliMe87A2XXJUiIgV7773OKVPaqW0YjK7yBtuoKZcfofu9ywOqmOga0cEGr9QUVXD5MWZDcsnu3UrnpqTQLRHL9FJovNPHkjzbK5thYXAphRzGKSV7vGiAwUbwkvo1SJwLs92D3bh7V-P8DuPOQQVRDD7K8rfFOdL1MVk3ofgdRtrvoOih9TKk5t3HElWpAn_u4JnaUPixEJJeF3ZX7MJiPgzE4CfRIKpmDPrXfFXjdIcC93ouPq5sBQVasQSMwU5W0_7w50sfS-Q';
        $user -> vsts_refresh_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsIng1dCI6Im9PdmN6NU1fN3AtSGpJS2xGWHo5M3VfVjBabyJ9.eyJuYW1laWQiOiJiMTNhY2NkMi00OTllLTZjZTAtYWY2NS0yYmY3OWJmMTAxN2EiLCJhY2kiOiJiM2VmZmI0Zi0yNTkyLTRkMzgtOGRmYy1lZmI2YWVhZDc1ZmEiLCJzY3AiOiJ2c28uY29kZSB2c28ucHJvamVjdF9tYW5hZ2UiLCJpc3MiOiJhcHAudnNzcHMudmlzdWFsc3R1ZGlvLmNvbSIsImF1ZCI6ImFwcC52c3Nwcy52aXN1YWxzdHVkaW8uY29tIiwibmJmIjoxNDg3Njc4NzQwLCJleHAiOjE1MTkyMTQ3NDB9.upcvx2hvXxFgItzzsMqF0xxzZBsOfqc3iKuoLvYZqSQLuIyIU1KydtgNlFfw9lnKfaITAGLN0XfMHI_PMVqGvv3zSLRHcUUPonTHNw4NjgxmhIcri5TdwgLhL4beyoYLY-5YjRgIfdtZDDlKhmuwZQsf0DKeSeuK9S1wxOgWJlJ3Qk8GyYP1ik5BsgDH5I5spIEpJ9vp7SFri8ovOYxBYahMO-S8SM0cYCpgtOFEURcMxnSsEu1-WLCmid0uXGkvFyxeHuRYEDK7d73uDgI5KiiPYuG3XjqB6pCHmRB3ZXdhYhJ02WJtZrd_mdjo_-Pl8DTGGbwRlFff4ucaeEwvcQ';
        $user -> save();
    }
}
