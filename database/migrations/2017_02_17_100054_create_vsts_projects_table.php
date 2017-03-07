<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVstsProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vsts_projects', function (Blueprint $table) {
            $table -> string('project_id');
            $table -> string('account_id');
            $table -> integer( 'module_id' ) -> unsigned() -> nullable();
            $table -> string('name') -> nullable();
            $table -> text('description') -> nullable();
            $table -> integer('revision');

            $table -> primary( array( 'project_id' ) );

            $table -> foreign( 'account_id' )
                -> references( 'account_id' )
                -> on( 'vsts_accounts' )
                -> onUpdate( 'cascade' )
                -> onDelete( 'no action' );

            $table -> foreign( 'module_id' )
                -> references( 'module_id' )
                -> on( 'modules' )
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
        Schema::dropIfExists('vsts_projects');
    }
}
