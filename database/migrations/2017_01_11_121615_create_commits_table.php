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
            $table -> string( 'comment' );
            $table -> string( 'repository_url' );
            $table -> timestamps();

            $table -> primary( [ 'commit_id' ] );
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
