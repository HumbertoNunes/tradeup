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

Route::patch('refunds/{refund}/approve', 'RefundController@approve');

Route::get('people/{person}/refunds/{year}/{month}', 'RefundReportController@monthly');

Route::get('people/{person}/export/refunds/{year}/{month}', 'RefundReportController@export');
