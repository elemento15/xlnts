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

Route::get('products', 'ProductsController@index');
Route::post('products/search-product', 'ProductsController@searchProduct');

Route::get('groups', 'GroupsController@index');

Route::get('movement_concepts', 'MovementConceptsController@index');

Route::resource('movements', 'MovementsController');

Route::resource('clients', 'ClientsController');
Route::post('clients/{id}/activate', 'ClientsController@activate');
Route::post('clients/{id}/deactivate', 'ClientsController@deactivate');

Route::resource('visits', 'VisitsController');
Route::resource('sales', 'SalesController');

Route::group(['middleware' => ['admin']], function () {
	Route::resource('users', 'UsersController');
	Route::post('users/{id}/activate', 'UsersController@activate');
	Route::post('users/{id}/deactivate', 'UsersController@deactivate');

	Route::resource('groups', 'GroupsController', [
		'except' => ['index']
	]);
	Route::post('groups/{id}/activate', 'GroupsController@activate');
	Route::post('groups/{id}/deactivate', 'GroupsController@deactivate');

	Route::resource('attributes', 'AttributesController');
	Route::post('attributes/{id}/activate', 'AttributesController@activate');
	Route::post('attributes/{id}/deactivate', 'AttributesController@deactivate');

	Route::resource('products', 'ProductsController', [
		'except' => ['index']
	]);
	Route::post('products/{id}/activate', 'ProductsController@activate');
	Route::post('products/{id}/deactivate', 'ProductsController@deactivate');
	Route::post('products/{id}/attributes', 'ProductsController@saveAttributes');

	Route::resource('movement_concepts', 'MovementConceptsController', [
		'except' => ['index']
	]);
	Route::post('movement_concepts/{id}/activate', 'MovementConceptsController@activate');
	Route::post('movement_concepts/{id}/deactivate', 'MovementConceptsController@deactivate');

	Route::post('movements/{id}/cancel', 'MovementsController@cancel');
});
