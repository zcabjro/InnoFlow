<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVstsAccountUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vsts_account_users', function (Blueprint $table) {
            $table -> string( 'account_id' );
            $table -> integer( 'user_id' ) -> unsigned();
            $table -> boolean( 'is_owner' );

            $table -> primary( array( 'account_id', 'user_id' ) );

            $table -> foreign( 'account_id' )
                -> references( 'account_id' )
                -> on( 'vsts_accounts' )
                -> onUpdate( 'cascade' )
                -> onDelete( 'cascade' );

            $table -> foreign( 'user_id' )
                -> references( 'user_id' )
                -> on( 'users' )
                -> onUpdate( 'cascade' )
                -> onDelete( 'cascade' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vsts_account_users');
    }
}
