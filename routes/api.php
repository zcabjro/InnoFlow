<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Auth routes
Route::post( 'login', 'AuthController@loginUser' );
Route::post( 'register', 'AuthController@registerUser' );
Route::get( 'innoflow', 'AuthController@isLoggedIn' );


// InnoFlow extension routes
Route::post( 'innovations', 'InnovationController@store' );


// Vsts routes
Route::post( 'commits', 'CommitController@store' );


// JWT token protected routes
Route::group( [ 'middleware' => 'jwt-auth' ], function () {

    Route::get( 'logout', 'AuthController@logoutUser' );
    Route::get( 'vsts', 'AuthController@isVstsAuthorized' );

    Route::get( 'innovations', 'InnovationController@index' );

    Route::post( 'classes', 'ModuleController@store' );
    Route::get( 'classes', 'ModuleController@index' );
    Route::get( 'classes/search', 'ModuleController@search' );
    Route::get( 'classes/{module}', 'ModuleController@show' ) -> middleware( 'module-auth' );
    Route::get( 'classes/admins/search', 'ModuleController@searchAdmin' );

    Route::get( 'projects', 'ProjectController@index' ) -> middleware( 'vsts-auth' );
    Route::group( [ 'middleware' => 'project-auth' ], function () {

        Route::get( 'projects/{vstsProject}', 'ProjectController@show' );
        Route::get( 'projects/{vstsProject}/commits', 'Project\CommitController@index' );
        Route::post( 'projects/{vstsProject}/enrol', 'ProjectController@enrol' ) -> middleware( 'vsts-auth' );
        Route::get( 'projects/{vstsProject}/commits/{commit}', 'Project\CommitController@show' );

    });

});