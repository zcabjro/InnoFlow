<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'users', function ( Blueprint $table ) {

            // innoflow
            $table -> increments( 'user_id' );
            $table -> string( 'email' ) -> unique();
            $table -> string( 'username' ) -> unique();
            $table -> string( 'password' );
            $table -> dateTime( 'last_update' ) -> nullable();

            // vsts
            $table -> string( 'vsts_profile_id' ) -> nullable();
            $table -> string( 'vsts_email' ) -> nullable();
            $table -> text( 'vsts_access_token' ) -> nullable();
            $table -> text( 'vsts_refresh_token' ) -> nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'users' );
    }
}
