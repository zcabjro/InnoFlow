<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodeReviewCommitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'code_review_commits', function ( Blueprint $table ) {

            $table -> integer( 'code_review_id' ) -> unsigned();
            $table -> string( 'commit_id' );

            $table -> primary( [ 'code_review_id', 'commit_id' ] );

            $table -> foreign( 'code_review_id' )
                -> references( 'code_review_id' )
                -> on( 'code_reviews' )
                -> onUpdate( 'cascade' )
                -> onDelete( 'cascade' );

            $table -> foreign( 'commit_id' )
                -> references( 'commit_id' )
                -> on( 'commits' )
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
        Schema::dropIfExists( 'code_review_commits' );
    }
}
