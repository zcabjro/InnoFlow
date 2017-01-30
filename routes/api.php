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
Route::post( 'login', 'Auth\AuthController@loginUser' );
Route::post( 'register', 'Auth\AuthController@registerUser' );


// InnoFlow extension routes
Route::post( 'innovation', 'IdeExtension\InnovationController@store' );


// JWT token protected routes
Route::group( [ 'middleware' => 'jwt-auth' ], function () {

    Route::get( 'logout', 'Auth\AuthController@logoutUser' );
    Route::get( 'token', 'Auth\AuthController@isAuthorized' );

    // VSTS token protected routes
    Route::group( [ 'middleware' => 'vsts-auth' ], function () {

        Route::post( '/commits', 'CommitController@create' );

    });

});


// VSTS callback route
Route::put( 'vsts/token/{id}', 'VSTS\TokenController@store' );