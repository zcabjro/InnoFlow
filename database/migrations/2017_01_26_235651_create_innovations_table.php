<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInnovationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'innovations', function ( Blueprint $table ) {
            $table -> increments( 'innovation_id' );
            $table -> text( 'code' );
            $table -> integer( 'user_id' ) -> unsigned();
            $table -> timestamps();

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
        Schema::dropIfExists( 'innovations' );
    }
}
