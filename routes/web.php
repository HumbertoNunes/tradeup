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

Route::get('/', function () {
    return view('welcome');
});

Route::get('refunds', 'RefundController@index');

Route::post('refunds', 'RefundController@store');

Route::patch('refunds/{refund}', 'RefundController@update');

Route::delete('refunds/{refund}', 'RefundController@destroy');

Route::get('people/{person}/refunds', 'PersonController@refunds');
