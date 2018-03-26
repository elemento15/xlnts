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

	Route::resource('groups', 'GroupsController');
	Route::post('groups/{id}/activate', 'GroupsController@activate');
	Route::post('groups/{id}/deactivate', 'GroupsController@deactivate');

	Route::resource('attributes', 'AttributesController');
	Route::post('attributes/{id}/activate', 'AttributesController@activate');
	Route::post('attributes/{id}/deactivate', 'AttributesController@deactivate');

	Route::resource('products', 'ProductsController');
	Route::post('products/{id}/activate', 'ProductsController@activate');
	Route::post('products/{id}/deactivate', 'ProductsController@deactivate');
});