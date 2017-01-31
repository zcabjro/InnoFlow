<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Index route
Route::get( '/', 'Web\WebController@getIndex' ) -> name( 'index' );


// VSTS callback route
Route::get( 'vsts/authorize', 'Web\WebController@getAuthorizeIndex' );