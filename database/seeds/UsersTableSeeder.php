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
            $user -> vsts_access_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsIng1dCI6Im9PdmN6NU1fN3AtSGpJS2xGWHo5M3VfVjBabyJ9.eyJuYW1laWQiOiJiMTNhY2NkMi00OTllLTZjZTAtYWY2NS0yYmY3OWJmMTAxN2EiLCJzY3AiOiJ2c28uY29kZSB2c28ucHJvamVjdF9tYW5hZ2UiLCJpc3MiOiJhcHAudnNzcHMudmlzdWFsc3R1ZGlvLmNvbSIsImF1ZCI6ImFwcC52c3Nwcy52aXN1YWxzdHVkaW8uY29tIiwibmJmIjoxNDg3NTI1MTU2LCJleHAiOjE0ODc1Mjg3NTZ9.2QX7QdSLsioTHB2TBSYoS1T020yHNu6HUK7cKbIyWIgZNCJEfN4RjGVC9VImrfOf6vNVC2gLrBAF4hxHtt95zlezELDJfYI2DLhYzxD4_E1QWJrjRgO1gOLGRPZAhlj_zO3_VpALUXs-J2AvIHI52rcRFKi-XQtj5GDG1UuNlyT7nHv1z_c33vfRuJC4sz5uwIIxVcZ292XXxoPSvVz1TEDTj3hfZIHtH3XnZ7xoxDNcxmAO-WOcrzDcxQN30VvSTpTHQICHAKtoC1BQkMsnWQadVLPZz9IvHTjNnboQ7rVXr7rWBRl6QgVcQ7G8aRV0nkRRiZKfRvWLP4euXuePrQ';
            $user -> vsts_refresh_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsIng1dCI6Im9PdmN6NU1fN3AtSGpJS2xGWHo5M3VfVjBabyJ9.eyJuYW1laWQiOiJiMTNhY2NkMi00OTllLTZjZTAtYWY2NS0yYmY3OWJmMTAxN2EiLCJhY2kiOiI0ZWY4MDg3OS0zODY5LTRhNTItOTdjYi01YzU3NDU5ZjIwNTkiLCJzY3AiOiJ2c28uY29kZSB2c28ucHJvamVjdF9tYW5hZ2UiLCJpc3MiOiJhcHAudnNzcHMudmlzdWFsc3R1ZGlvLmNvbSIsImF1ZCI6ImFwcC52c3Nwcy52aXN1YWxzdHVkaW8uY29tIiwibmJmIjoxNDg3NTI1MTU2LCJleHAiOjE1MTkwNjExNTZ9.WMT2R8JFC2MTQWvDUb3gRf_GCgAExtOfiebsX273Iz_IdktBmC3Zj2KnrK2eoV80nQi-lN0YmVZUmFGruboAi4TROzmlCtvO_ZHpGx1nyD1vSp5PJW60I1IoYJhjfoVfYgys1MIWY8vuccBnzQZ6DW8ePRPBVkP-zRDX6dJp8W2nA_awYcNIEL00JFId9EHiIux_u9xAvgQNmeFQGitbYrUUhm_kvfSYEzGZak_KKKPNzPJ7kCtu75PbsGBqirKJMXhOXFz3OdBGimhLspWEjBUM86i88JxMOKi_LKC0kQAJLzIwVeXBsKYerD7KWVXXUsJKN0Ot6mACjHN26xTK0A';
            $user -> save();
        }
    }
}
