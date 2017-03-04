<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'comments', function ( Blueprint $table ) {

            $table -> increments( 'comment_id' );
            $table -> integer( 'user_id' ) -> unsigned();
            $table -> integer( 'code_review_id' ) -> unsigned();
            $table -> text( 'message' );
            $table -> timestamps();

            $table -> foreign( 'user_id' )
                -> references( 'user_id' )
                -> on( 'users' )
                -> onUpdate( 'cascade' )
                -> onDelete( 'cascade' );

            $table -> foreign( 'code_review_id' )
                -> references( 'code_review_id' )
                -> on( 'code_reviews' )
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
        Schema::dropIfExists( 'comments' );
    }
}
