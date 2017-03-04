<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodeReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'code_reviews', function ( Blueprint $table ) {
            $table -> increments( 'code_review_id' );
            $table -> integer( 'user_id' ) -> unsigned();
            $table -> string( 'project_id' );
            $table -> timestamps();

            $table -> foreign( 'user_id' )
                -> references( 'user_id' )
                -> on( 'users' )
                -> onUpdate( 'cascade' )
                -> onDelete( 'no action' );

            $table -> foreign( 'project_id' )
                -> references( 'project_id' )
                -> on( 'vsts_projects' )
                -> onUpdate( 'cascade' )
                -> onDelete( 'no action' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'code_reviews' );
    }
}
