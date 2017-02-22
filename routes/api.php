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


// InnoFlow extension routes
Route::post( 'innovations', 'InnovationController@store' );


// JWT token protected routes
Route::group( [ 'middleware' => 'jwt-auth' ], function () {

    Route::get( 'logout', 'AuthController@logoutUser' );
    Route::get( 'vsts', 'AuthController@isVstsAuthorized' );

    Route::get( 'innovations', 'InnovationController@index' );

    Route::post( 'classes', 'ModuleController@store' );
    Route::get( 'classes', 'ModuleController@index' );
    Route::get( 'classes/{module}', 'ModuleController@show' );
    Route::get( 'classes/admins/search', 'ModuleController@searchAdmin' );

    Route::get( 'projects/{vstsProject}', 'ProjectController@show' );
    Route::post( 'projects/{vstsProject}/enrol', 'ProjectController@enrol' );

    // Vsts token protected routes
    Route::group( [ 'middleware' => 'vsts-auth' ], function () {

        Route::post( 'commits', 'CommitController@create' );

        Route::get( 'projects', 'ProjectController@index' );

    });

});