<?php

use Illuminate\Http\Request;

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

Route::namespace('Auth')->group(function () {

	Route::post('register', 'RegisterController@register');

	Route::post('login', 'LoginController@login');
});

Route::middleware('auth:api')->group(function () {

	Route::get('refunds', 'RefundController@index');

	Route::post('refunds', 'RefundController@store');

	Route::patch('refunds/{refund}', 'RefundController@update');

	Route::delete('refunds/{refund}', 'RefundController@destroy');

	Route::patch('refunds/{refund}/approve', 'RefundController@approve');

	Route::post('refunds/{refund}/image', 'RefundController@upload');

	Route::get('people/{person}/refunds/{year}/{month}', 'RefundReportController@monthly');

	Route::get('people/{person}/export/refunds/{year}/{month}', 'RefundReportController@export');
});