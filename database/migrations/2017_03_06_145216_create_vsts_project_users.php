<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVstsProjectUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vsts_project_users', function (Blueprint $table) {
            $table -> string( 'project_id' );
            $table -> integer( 'user_id' ) -> unsigned();

            $table -> primary( array( 'project_id', 'user_id' ) );

            $table -> foreign( 'project_id' )
                -> references( 'project_id' )
                -> on( 'vsts_projects' )
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
        Schema::dropIfExists( 'vsts_project_users' );
    }
}
