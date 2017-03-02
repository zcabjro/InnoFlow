<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateCommitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'commits', function ( Blueprint $table )
        {
            $table -> string( 'commit_id' );
            $table -> string( 'project_id' );
            $table -> string( 'comment' );
            $table -> date( 'date' );
            $table -> string( 'details_url' );
            $table -> string( 'profile_id' ) -> nullable();
            $table -> integer( 'adds_counter' ) ->default( 0 );
            $table -> integer( 'edits_counter' ) ->default( 0 );
            $table -> string( 'web_url' ) -> nullable();
            $table -> boolean( 'is_complete' ) ->default( false );

            $table -> primary( [ 'commit_id' ] );

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
        Schema::dropIfExists( 'commits' );
    }
}
