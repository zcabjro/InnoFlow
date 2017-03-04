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
            $table -> dateTimeTz( 'date' );
            $table -> string( 'profile_id' );
            $table -> integer( 'adds_counter' ) ->default( 0 );
            $table -> integer( 'edits_counter' ) ->default( 0 );
            $table -> integer( 'delets_counter' ) ->default( 0 );
            $table -> string( 'web_url' );

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
