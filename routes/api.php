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
    Route::get( 'token', 'AuthController@isAuthorized' );

    Route::get( 'innovations', 'InnovationController@index' );
    Route::get( 'users/search', 'UserController@search' );

    Route::get( 'classes', 'ModuleController@index' );
    Route::post( 'classes', 'ModuleController@store' );
    Route::get( 'classes/{module}', 'ModuleController@show' );

    Route::get( 'projects/{vstsProject}', 'ProjectController@show' );
    Route::post( 'projects/{vstsProject}/enrol', 'ProjectController@enrol' );

    // VstsAccount token protected routes
    Route::group( [ 'middleware' => 'vsts-auth' ], function () {

        Route::post( 'commits', 'CommitController@create' );

        Route::get( 'projects', 'ProjectController@index' );

    });

});