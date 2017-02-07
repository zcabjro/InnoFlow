<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'modules', function ( Blueprint $table ) {
            $table -> increments( 'module_id' );
            $table -> string( 'name' );
            $table -> string( 'description' );
            $table -> string( 'code' );
            $table -> string( 'key' );
            $table -> integer( 'user_id' ) -> unsigned() -> nullable();
            $table -> timestamps();
        });

        Schema::table( 'modules', function( Blueprint $table ) {
            $table -> foreign( 'user_id' )
                -> references( 'user_id' )
                -> on( 'users' )
                -> onUpdate( 'cascade' )
                -> onDelete( 'set null' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
