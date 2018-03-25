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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('roles', 'RolesController');

Route::group(['middleware' => ['admin']], function () {
	Route::resource('users', 'UsersController');
	Route::post('users/{id}/activate', 'UsersController@activate');
	Route::post('users/{id}/deactivate', 'UsersController@deactivate');
});