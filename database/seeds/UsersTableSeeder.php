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
        $user -> vsts_access_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsIng1dCI6Im9PdmN6NU1fN3AtSGpJS2xGWHo5M3VfVjBabyJ9.eyJuYW1laWQiOiJiMTNhY2NkMi00OTllLTZjZTAtYWY2NS0yYmY3OWJmMTAxN2EiLCJzY3AiOiJ2c28uY29kZSB2c28ucHJvamVjdF9tYW5hZ2UiLCJpc3MiOiJhcHAudnNzcHMudmlzdWFsc3R1ZGlvLmNvbSIsImF1ZCI6ImFwcC52c3Nwcy52aXN1YWxzdHVkaW8uY29tIiwibmJmIjoxNDg3NjI2NDI4LCJleHAiOjE0ODc2MzAwMjh9.jSguNPobrffO_QzZRc4VqWLK7DxtQQY9-489mTTzzM-CdvpqlXFUTs6qxR9UsWwg_FYurkjkflucjustdFDNnR2mt3PLaiQFVHiKogiAWOdQ8Iwwx7M-Dw8EPI0IjW-vcrG-xzCNZcM-7M4Ho4xQARqsF4V8AXB4Vl_MzVnR8-UyMbNt43qZmuoJqhVbqe_g0qrXFOYtKfuh3DfFluWAuHpmvG24NmB2MDNIBzEQzSe3GiOnEuPAoQECaNbdj9iE4_eYx6PVuEumd9akSTggq-O2PqciS8sdJjRMU2GCYVIlK6BDAaQTfPD5XevA8iT0ahsA6D7vQey2UnXDNQUx4Q';
        $user -> vsts_refresh_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsIng1dCI6Im9PdmN6NU1fN3AtSGpJS2xGWHo5M3VfVjBabyJ9.eyJuYW1laWQiOiJiMTNhY2NkMi00OTllLTZjZTAtYWY2NS0yYmY3OWJmMTAxN2EiLCJhY2kiOiI0N2M2NGM3NS0xN2M3LTQwNjQtOWI4ZC0yNGYyMDNkMzA4OTciLCJzY3AiOiJ2c28uY29kZSB2c28ucHJvamVjdF9tYW5hZ2UiLCJpc3MiOiJhcHAudnNzcHMudmlzdWFsc3R1ZGlvLmNvbSIsImF1ZCI6ImFwcC52c3Nwcy52aXN1YWxzdHVkaW8uY29tIiwibmJmIjoxNDg3NjI2NDI4LCJleHAiOjE1MTkxNjI0Mjh9.niEs0B4GfjKxa3ohzVGbdfr9UtoPvuYFbJv2TF12zqeH-x5bksbeuTXw4s9MECH8LmbNjn34nyjEwZyGjpYGcHVgR2cWVwt81vGop53W3yHLxo25Hr9bLUN3gaSX0Tgr1zm3YcFy0FOWYAwBJI-izI2frwUNdmsFdc0EK-LQP2u9nNst4vbeo99-TbQyRgZY6L1JWA_o7n6PnWbBHoL1Yqehs_H5E7mlTDHhMNaOW0oM_tz7hbxwMIx-8DZElLq9bNp5TkPpITiURsUADvlt_gMdIrFuBGgOKekwhGH1sE9b4D7vMO2yprII1_HbLPO1xulWj471tKjwVRQYtGQSRA';
        $user -> save();
    }
}
