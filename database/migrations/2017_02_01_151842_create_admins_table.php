<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'admins', function( Blueprint $table ) {
            $table -> integer( 'user_id' ) -> unsigned();
            $table -> integer( 'module_id' ) -> unsigned();
            $table -> boolean( 'is_owner' );

            $table -> primary( array( 'user_id', 'module_id' ) );

            $table -> foreign( 'user_id' )
                -> references( 'user_id' )
                -> on( 'users' )
                -> onUpdate( 'cascade' )
                -> onDelete( 'cascade' );

            $table -> foreign( 'module_id' )
                -> references( 'module_id' )
                -> on( 'modules' )
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
        Schema::dropIfExists( 'admins' );
    }
}
