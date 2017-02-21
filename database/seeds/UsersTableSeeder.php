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
        $user -> vsts_access_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsIng1dCI6Im9PdmN6NU1fN3AtSGpJS2xGWHo5M3VfVjBabyJ9.eyJuYW1laWQiOiJiMTNhY2NkMi00OTllLTZjZTAtYWY2NS0yYmY3OWJmMTAxN2EiLCJzY3AiOiJ2c28uY29kZSB2c28ucHJvamVjdF9tYW5hZ2UiLCJpc3MiOiJhcHAudnNzcHMudmlzdWFsc3R1ZGlvLmNvbSIsImF1ZCI6ImFwcC52c3Nwcy52aXN1YWxzdHVkaW8uY29tIiwibmJmIjoxNDg3NzAwMjM4LCJleHAiOjE0ODc3MDM4Mzh9.16RP6zrqFF6HyQYSfXJl6E-Lc5m2BjRVEOfEKO-tJr76GVGZV3E3p0WLmwNnV2n2Mm5AUdUKrBvZdpN07oVxSJCYNB8cGTuc72riwHdUkFVcRRLLhyQ7O-HHE_-QqbvncV3fl2S27YAom62Gn-DL5HfSXcGkGhuDbj8V5KY2ZJVmN0Yyut6zn7c5iFqkYO5S59Vrtxfp-MA4NNtS_Q_89Tz4EyAvVxf2gCTstUP10d8oR_Bv5TBVpEjwmjpAyL_OQObTEgx8rb4FQPu1TpFrC36_v4_uBNmbqoQuI7V1qY9hkLP4JB5GQiLKEDOOW7C8LIa4rZ5W7M1omyys0vcjLA';
        $user -> vsts_refresh_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsIng1dCI6Im9PdmN6NU1fN3AtSGpJS2xGWHo5M3VfVjBabyJ9.eyJuYW1laWQiOiJiMTNhY2NkMi00OTllLTZjZTAtYWY2NS0yYmY3OWJmMTAxN2EiLCJhY2kiOiI3MzU4M2JhOS1mOGJiLTQ0ODItOGQ3My05YThiM2M0OTM0MTEiLCJzY3AiOiJ2c28uY29kZSB2c28ucHJvamVjdF9tYW5hZ2UiLCJpc3MiOiJhcHAudnNzcHMudmlzdWFsc3R1ZGlvLmNvbSIsImF1ZCI6ImFwcC52c3Nwcy52aXN1YWxzdHVkaW8uY29tIiwibmJmIjoxNDg3NzAwMjM4LCJleHAiOjE1MTkyMzYyMzh9.PlMo2N8cX0V1qchQM0g2zpPk3k8tAQse-b1hwqmkeNTBvPqVaKTsjfws-paP8csrNPRa8Onp5v1zNmDzNV5GK0h9JO0xfjQ2xhxVvtl_pG4Iz723ZB94ku2DSBAHy_zDxdQPsHNvL57YyqCI6g4t90R5OrJN2inwLisde6AZ2xXGd06641ODu8-a0wXUkW_P9YW5UMFnhG3U4kaHZ6gKyxaw0jtUfcPKJe1udnbVZS260tEKvKXeolKqz7KatzIOIelXelNCu0PANfrzrSxRT8NKdfejvcCmbHjxwsb84xZeRG9Tv5elY0v1Q3bji0WDczmGRgEUByNNPj0HZp73kg';
        $user -> save();
    }
}
