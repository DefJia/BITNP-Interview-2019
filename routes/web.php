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
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Route::get('/', function () { return redirect('/list/0/0'); });

Route::get('/', function () {
    return view('welcome');
});

Route::get('/list/{date}/{room}', 'DBController@show_list')->name('list');
Route::get('/action/{aid}/{uid}', 'DBController@handle_action')->name('action');
Route::get('/info/{id}', 'DBController@show_info')->name('info');

Route::post('insert_data', 'DBController@insert_data')->name('insert_data');

Route::get('paris', 'DBController@paris')->name('paris');

/*
Route::get('/login', function () {
    return cas()->authenticate();
});

Route::middleware('cas.auth')->get('/logout', function () {
    cas()->logout();
});

Route::middleware('cas.auth')->get('/user', function () {
    return cas()->getAttributes();
});
*/
